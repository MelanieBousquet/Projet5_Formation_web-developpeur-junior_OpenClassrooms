<?php 

namespace AppBundle\Entity\Password;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\Expression(
 *   expression="this.getOldPassword() !== this.getNewPassword()",
 *   message="L'ancien mot de passe et le nouveau de passe ne doivent pas être identiques !"
 * )
 */
class ChangePassword {
    /**
      *
      * @var UserInterface
      */
    private $user;
 
    /**
     * @Assert\NotBlank
     */
    private $oldPassword;
 
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min=6,
     *      minMessage = "Le mot de passe doit contenir au moins 6 caractères"
     *      )
     */
    private $newPassword;
 
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
 
    public function getUser()
    {
        return $this->user;
    }
 
    public function getSalt()
    {
        return $this->user->getSalt();
    }
 
    public function getOldPassword()
    {
        return $this->oldPassword;
    }
 
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
 
    public function getNewPassword()
    {
        return $this->newPassword;
    }
 
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}