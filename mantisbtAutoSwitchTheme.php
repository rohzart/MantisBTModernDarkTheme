<?php

class mantisbtAutoSwitchThemePlugin
extends MantisPlugin
{
	const CFG_ENABLED = 'plugin_mantisbtAutoSwitchTheme_enabled';

	function register()
	{
		$this->name = 'MantisBT Auto Switch Theme';
		$this->description = 'MantisBT theme which switches between light and dark modes in the day and at night, respectively.';

		$this->version = '2.0.0';
		$this->requires = array(
			'MantisCore' => '2.0.0',
		);

		$this->author = '@rohzart';
		$this->url = 'https://github.com/rohzart/mantisbtAutoSwitchTheme';
	}

	function hooks()
	{
		return array(
			'EVENT_LAYOUT_RESOURCES' => 'add_css',

			'EVENT_ACCOUNT_PREF_UPDATE_FORM' => 'account_update_form',
			'EVENT_ACCOUNT_PREF_UPDATE' => 'account_update',
		);
	}

	function add_css( $p_event )
	{
		if( $this->is_enabled() )
			echo '<link rel="stylesheet" type="text/css" href="' . plugin_file( 'styles.min.css' ) . '" />' . "\n";
	}

	function is_enabled()
	{
		return auth_is_user_authenticated() && config_get( self::CFG_ENABLED, false, auth_get_current_user_id(), ALL_PROJECTS );;
	}

	function account_update_form( $p_event, $p_user_id )
	{
		echo '<tr>' .
				 '<td class="category">' .
					'Enable Theme' .
				 '</td>' .
				 '<td>' .
					 '<input id="DarkModeSwitch" type="checkbox" name="' . self::CFG_ENABLED . '" value="1" ' . ( $this->is_enabled() ? 'checked' : '' ) . '/>' .
				 '</td>' .
			 '</tr>';
	}

	function account_update( $p_event, $p_user_id )
	{
		config_set( self::CFG_ENABLED, gpc_get_bool( self::CFG_ENABLED, false ), $p_user_id, ALL_PROJECTS );
	}
}