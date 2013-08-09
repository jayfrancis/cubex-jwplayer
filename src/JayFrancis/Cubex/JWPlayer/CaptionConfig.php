<?php
namespace JayFrancis\Cubex\JWPlayer;

class CaptionConfig
{

  private $_default;
  private $_url;
  private $_label;

  function __construct($url, $label, $default = false)
  {
    $this
    ->setUrl($url)
    ->setLabel($label)
    ->setDefault($default);
  }

  public function setDefault($default)
  {
    $this->_default = (bool)$default;

    return $this;
  }

  public function getDefault()
  {
    return $this->_default;
  }

  /**
   * @param $url
   *
   * @return CaptionConfig
   */
  public function setUrl($url)
  {
    $this->_url = $url;

    return $this;
  }

  public function getUrl()
  {
    return $this->_url;
  }

  /**
   * @param $label
   *
   * @return CaptionConfig
   */
  public function setLabel($label)
  {
    $this->_label = $label;

    return $this;
  }

  public function getLabel()
  {
    return $this->_label;
  }
}
