<?php

namespace App\Model;

use Kdyby\Doctrine\EntityDao;
use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{

	private $userRepository;


	public function __construct(EntityDao $userRepository)
	{
		$this->userRepository = $userRepository;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$user = $this->userRepository->findOneBy(array('username' => $username));
		/** @var \App\Model\Entities\UserEntity $user */

		if (!$user) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (Passwords::verify($password, $user->password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($user->password)) {
			$user->password = Passwords::hash($password);
			$this->userRepository->save($user);
		}

		return $user;
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($username, $password)
	{
		$user = new Entities\UserEntity();
		$user->setUsername($username);
		$user->setPassword(Passwords::hash($password));

		$this->userRepository->save($user);
	}

}
