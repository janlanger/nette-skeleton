<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette;


/**
 * @ORM\Entity
 * @ORM\Table(name="data_users")
 */
class UserEntity extends BaseEntity implements Nette\Security\IIdentity
{

	use Identifier;

	/**
	 * @ORM\Column(length=50)
	 * @var string
	 */
	protected $username;

	/**
	 * @ORM\Column(length=60)
	 * @var string
	 */
	protected $password;

	/**
	 * @ORM\Column(length=20)
	 * @var string
	 */
	protected $role = '';


	/**
	 * @return array
	 */
	public function toArray()
	{
		$prop = array('id', 'username', 'password', 'role');
		$arr = array();
		foreach ($prop as $var) {
			$arr[$var] = $this->$var;
		}

		return $arr;
	}

	/**
	 * Returns a list of roles that the user is a member of.
	 * @return array
	 */
	function getRoles()
	{
		return [$this->role];
	}
}
