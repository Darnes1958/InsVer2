<?php

namespace App\Filament\Pages\Auth;

use App\Models\Customers;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends \Filament\Pages\Auth\Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getCompCodeFormComponent(),
                        $this->getIdFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    protected function getCompCodeFormComponent(): Component
    {
        return TextInput::make('CompCode')
            ->label('كود الشركة')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }
    protected function getIdFormComponent(): Component
    {
        return TextInput::make('id')
            ->label('رقم المستخدم')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'id' => $data['id'],
            'password' => $data['password'],

        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.id' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();
        $code=$data['CompCode'];

       $data=Arr::except($data,'CompCode');




        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if ($user->id!=1)
        if (! Customers::where('Company',Auth::user()->company)->where('CompCode',$code)->exists()) {
            $this->throwFailureValidationException();
        }



        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
