<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\Actions\Auth\RegisterUserAction;
use App\DTOs\Auth\RegisterDTO;
use App\Models\User;
use App\Services\Auth\PasswordValidationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    public int $step = 1;

    public string $email = '';

    public string $username = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public string $name = '';

    public string $phone = '';

    public string $address_line_1 = '';

    public string $city = '';

    public string $state = '';

    public string $postal_code = '';

    public string $country = '';

    #[Rule('nullable|string|max:255')]
    public string $address_line_2 = '';

    public string $company = '';

    public bool $emailExists = false;

    public array $passwordCriteria = [
        'length' => false,
        'uppercase' => false,
        'lowercase' => false,
        'digit' => false,
        'special' => false,
    ];

    public int $passwordScore = 0;

    public string $passwordLabel = '';

    public bool $breachChecked = false;

    public int $breachCount = 0;

    public bool $breachChecking = false;

    private PasswordValidationService $passwordValidationService;

    public function boot(PasswordValidationService $passwordValidationService): void
    {
        $this->passwordValidationService = $passwordValidationService;
    }

    protected function rules(): array
    {
        return match ($this->step) {
            1 => ['email' => ['required', 'string', 'email', 'max:255']],
            2 => [
                'password' => [
                    'required', 'string', 'min:8',
                    'regex:/[A-Z]/', 'regex:/[a-z]/',
                    'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/',
                ],
                'passwordConfirmation' => ['required', 'same:password'],
            ],
            3 => ['name' => ['required', 'string', 'max:255']],
            default => [],
        };
    }

    protected $messages = [
        'password.regex' => 'The password must contain at least one uppercase letter, lowercase letter, number, and special character.',
    ];

    public function updatedEmail(): void
    {
        $this->resetValidation('email');

        if (blank($this->email)) {
            $this->emailExists = false;

            return;
        }

        $this->validateOnly('email');
        $this->emailExists = User::where('email', $this->email)->exists();
    }

    public function updatedPassword(): void
    {
        if (blank($this->password)) {
            $this->resetPasswordValidation();

            return;
        }

        $strength = $this->passwordValidationService->checkStrength($this->password);
        $this->passwordCriteria = $strength['criteria'];
        $this->passwordScore = $strength['score'];
        $this->passwordLabel = $strength['label'];

        $this->checkBreach();
    }

    public function checkBreach(): void
    {
        if (strlen($this->password) < 8) {
            return;
        }

        $this->breachChecking = true;
        $this->breachChecked = false;

        $this->breachCount = $this->passwordValidationService->checkBreach($this->password);
        $this->breachChecked = true;
        $this->breachChecking = false;
    }

    private function resetPasswordValidation(): void
    {
        $this->passwordCriteria = [
            'length' => false, 'uppercase' => false,
            'lowercase' => false, 'digit' => false, 'special' => false,
        ];
        $this->passwordScore = 0;
        $this->passwordLabel = '';
        $this->breachChecked = false;
        $this->breachCount = 0;
        $this->breachChecking = false;
    }

    public function nextStep(): void
    {
        $this->validate();

        if ($this->step === 1 && $this->emailExists) {
            $this->addError('email', __('An account with this email already exists.'));

            return;
        }

        $this->step++;
        $this->resetValidation();
    }

    public function previousStep(): void
    {
        $this->step--;
        $this->resetValidation();
    }

    public function submit(RegisterUserAction $action): void
    {
        $this->validate();

        $user = $action->execute(new RegisterDTO(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            username: $this->username ?: null,
            phone: $this->phone ?: null,
            company: $this->company ?: null,
            address_line_1: $this->address_line_1 ?: null,
            city: $this->city ?: null,
            state: $this->state ?: null,
            postal_code: $this->postal_code ?: null,
            country: $this->country ?: null,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
        ));

        Auth::login($user);

        $this->redirect(app(RedirectAuthenticatedUser::class)->execute());
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
