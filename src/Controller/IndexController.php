<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/2/28 12:28
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{

	/**
	 * @Route("/", methods={"ANY"})
	 * @return Response
	 */
	public function hello(){
		return new Response("<p>hello world!</p>");
	}
}