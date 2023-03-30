<?php

namespace GeekBrains\LevelTwo\Blog\Person;

use GeekBrains\LevelTwo\Blog\Models\User;

class Name extends User
{
  public function __construct(
	  private string $firstname,
	  private string $lastname
  )
  {
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