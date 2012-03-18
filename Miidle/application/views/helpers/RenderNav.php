<?php
class Zend_View_Helper_RenderNav extends Zend_View_Helper_Abstract
{
	public function renderNav($pagina_atual, $total_paginas, $baseurl)
	{
		if(1 == $total_paginas)
			return array();

		$s_total_paginas = $total_paginas;
		if($total_paginas < 10)
			$s_total_paginas = '0' . $total_paginas;

		$lim_inf = $pagina_atual - 3;
		$lim_sup = $pagina_atual + 3;
		if($lim_inf < 2) {
			$lim_inf = 2;
			$lim_sup = 7;
		}
		if($lim_sup > $total_paginas - 1) {
			$lim_sup = $total_paginas - 1;
			$lim_inf = $lim_sup - 7;
			if($lim_inf < 2) $lim_inf = 2;
		}

		$prim = '1';

		while(strlen($prim) < strlen($s_total_paginas))
			$prim = '0' . $prim;

		$paginas = array();
		$class = '';
		if(1 == $pagina_atual) {
			$class = ' class="selected"';
		}
		$paginas[] = '<a' . $class .' href="' . $baseurl . '1">' . $prim . '</a>';
		array('exibir' => $prim, 'pagina' => $baseurl . 1);
		if($lim_inf > 2)
			$paginas[] = '...';
		for($i = $lim_inf; $i <= $lim_sup; $i++) {
			$s_i = $i;
			while(strlen($s_i) < strlen($s_total_paginas))
				$s_i = '0' . $s_i;
			$class = '';
			if($i == $pagina_atual) {
				$class = ' class="selected"';
			}
			$paginas[] = '<a' . $class . ' href="' . $baseurl . $i . '">' . $s_i . '</a>';
		}
		if($lim_sup < $total_paginas - 1)
			$paginas[] = '...';
		$class = '';
		if($total_paginas == $pagina_atual) {
			$class = ' class="selected"';
		}
		$paginas[] = '<a'. $class . ' href="' . $baseurl . $total_paginas . '">' . $s_total_paginas . '</a>';
		return $paginas;
	}
}