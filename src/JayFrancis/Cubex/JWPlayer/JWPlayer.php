<?php
namespace JayFrancis\Cubex\JWPlayer;

use Cubex\Dispatch\Utils\ListenerTrait;
use Cubex\Dispatch\Utils\RequireTrait;

class JWPlayer
{

  use ListenerTrait;
  use RequireTrait;

  private $_flashPlayerUrl;
  private $_html5PlayerUrl;
  private $_captionConfigs = array();
  private $_elementId;
  private $_imageUrl;
  private $_videoUrl;
  private $_width = 640;
  private $_height = 360;

  function __construct()
  {
    $this->_listen(__NAMESPACE__);

    $this->_elementId = uniqid('video-');
  }

  public function getHtml()
  {
    $output[] = sprintf(
      'jwplayer("%s").setup(%s);',
      $this->getElementId(),
      json_encode($this->_getConfigArray())
    );

    $this->addJsBlock(implode(PHP_EOL, $output));

    $output   = [];
    $output[] = sprintf(
      '<div id="%s">Loading ...</div>',
      $this->getElementId()
    );

    return implode(PHP_EOL, $output);
  }

  public function addJs()
  {
    $this->requireJs('jwplayer');
    return $this;
  }

  /**
   * @return CaptionConfig[]
   */
  public function getCaptionConfigs()
  {
    return $this->_captionConfigs;
  }

  public function addCaptionConfig($url, $label = 'On', $default = false)
  {
    $this->_captionConfigs[$url] = new CaptionConfig($url, $label, $default);

    return $this;
  }

  public function getElementId()
  {
    return $this->_elementId;
  }

  public function setElementId($elementId)
  {
    $this->_elementId = $elementId;
  }

  public function setVideoUrl($url = '')
  {
    $this->_videoUrl = $url;

    return $this;
  }

  public function getVideoUrl()
  {
    if(!$this->_videoUrl)
    {
      throw new \Exception('Video URL not set');
    }

    return $this->_videoUrl;
  }

  public function setImageUrl($url = '')
  {
    $this->_imageUrl = $url;

    return $this;
  }

  public function getImageUrl()
  {
    return $this->_videoUrl;
  }

  public function setWidth($width = 640)
  {
    $width = (int)$width;
    if($width < 1)
    {
      throw new \Exception('Width cannot be ' . $width);
    }
    $this->_width = $width;

    return $this;
  }

  public function getWidth()
  {
    return $this->_width;
  }

  public function setHeight($height = 360)
  {
    $height = (int)$height;
    if($height < 1)
    {
      throw new \Exception('Height cannot be ' . $height);
    }
    $this->_height = $height;

    return $this;
  }

  public function getHeight()
  {
    return $this->_height;
  }

  public function setFlashPlayerUrl($flashPlayerUrl)
  {
    $this->_flashPlayerUrl = $flashPlayerUrl;

    return $this;
  }

  public function getFlashPlayerUrl()
  {
    if(isset($this->_flashPlayerUrl))
    {
      return $this->_flashPlayerUrl;
    }

    return $this->getDispatchUrl('jwplayer.flash.swf');
  }

  public function setHtml5PlayerUrl($html5PlayerUrl)
  {
    $this->_html5PlayerUrl = $html5PlayerUrl;

    return $this;
  }

  public function getHtml5PlayerUrl()
  {
    if(isset($this->_html5PlayerUrl))
    {
      return $this->_html5PlayerUrl;
    }

    return $this->getDispatchUrl('jwplayer.html5.js');
  }

  private function _getConfigArray()
  {
    $config = [
      'file'        => $this->getVideoUrl(),
      'height'      => $this->getHeight(),
      'width'       => $this->getWidth(),
      'flashplayer' => $this->getFlashPlayerUrl(),
      'html5player' => $this->getHtml5PlayerUrl(),
    ];

    // Add image
    if($this->getImageUrl())
    {
      $options['image'] = $this->getImageUrl();
    }

    // Add captions
    if($this->getCaptionConfigs())
    {
      $config['tracks'] = [];

      foreach($this->getCaptionConfigs() as $captionConfig)
      {
        $config['tracks'][] = [
          'file'    => $captionConfig->getUrl(),
          'label'   => $captionConfig->getLabel(),
          'kind'    => 'captions',
          'default' => true,
        ];
      }
    }

    return $config;
  }
}
