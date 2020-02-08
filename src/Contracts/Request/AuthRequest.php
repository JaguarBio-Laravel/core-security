<?php
namespace Jaguar\Core\Security\Contracts\Request;

interface AuthRequest {	

	public function getPasswordValue(array $credentials);

	public function getPasswordAttribute();
}