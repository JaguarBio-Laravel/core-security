<?php
namespace Jaguar\Core\Security\Request;

use Jaguar\Core\Security\Contracts\Request\AuthRequest as AuthRequestContract;

class UserAuthRequest implements AuthRequestContract {
	
	protected $passwordAttribute;	

	public function getPasswordValue(array $credentials) {
		return $credentials[$this->passwordAttribute];
	}

	public function getPasswordAttribute() {
		return $this->passwordAttribute;
	}
}