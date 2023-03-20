<?php

namespace GeekBrains\LevelTwo\Blog\Person;

use GeekBrains\LevelTwo\Blog\Models\User;

class Name extends User
{
  private string $firstname, $lastname;

  /**
   * @param string $firstname
   * @param string $lastname
   */
  public function __construct(string $firstname, string $lastname)
  {
    $this->firstname = $firstname;
    $this->lastname = $lastname;
  }

  /**
   * @return string
   */
  public function first(): string
  {
    return $this->firstname;
  }

  /**
   * @return string
   */
  public function last(): string
  {
    return $this->lastname;
  }

  /**
   * @return string
   */
  public function __toString(): string
  {
    return $this->firstname . ' ' . $this->lastname;
  }
}