<?php
namespace Eduxplus\WebsiteBundle\Message;

class Msg
{
    private $content;
    
    public function __construct(string $content){
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
