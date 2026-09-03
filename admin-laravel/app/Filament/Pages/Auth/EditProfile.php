<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends BaseEditProfile
{
    public function getTitle(): string
    {
        return 'Đổi mật khẩu tài khoản';
    }

    public static function getLabel(): string
    {
        return 'Đổi mật khẩu';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getCurrentPasswordFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getCurrentPasswordFormComponent(): Component
    {
        return TextInput::make('currentPassword')
            ->label('Mật khẩu hiện tại')
            ->password()
            ->revealable()
            ->required()
            ->currentPassword()
            ->dehydrated(false);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Mật khẩu mới')
            ->password()
            ->revealable()
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
            ->same('passwordConfirmation');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Xác nhận mật khẩu mới')
            ->password()
            ->revealable()
            ->required()
            ->dehydrated(false);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đổi mật khẩu thành công!';
    }
}
