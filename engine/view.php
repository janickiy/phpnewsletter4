<?php

class View
{
	public function generate($template_view, $data = null)
	{
		global $PNSL;
		
		include $PNSL["system"]["dir_root"].$PNSL["system"]["dir_views"].$template_view;
	}
}

?>
