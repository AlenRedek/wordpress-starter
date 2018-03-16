<?php

use \DrewM\MailChimp\MailChimp;
require_once("src/MailChimp.php");

class MailChimpApp{
	private $api_key;
	private $form;
	private $message;
	private $email_only;

	public function __construct($api_key=''){

		$this->api_key = $api_key;

		if(function_exists('get_field')){
			$this->api_key = get_field('mc_api_key', 'options') ? get_field('mc_api_key', 'options') : $this->api_key;
		}

		add_action('wp_enqueue_scripts', array($this, 'include_scripts'));

		add_action('wp_ajax_mh_mailchimp_app', array($this, 'mh_mailchimp_app'));
		add_action('wp_ajax_nopriv_mh_mailchimp_app', array($this, 'mh_mailchimp_app'));
	}

	public function include_scripts(){

		$mailchimp_dir = str_replace(get_stylesheet_directory(), '', dirname(__FILE__));
		wp_enqueue_style('mh_mailchimp', get_stylesheet_directory_uri() . $mailchimp_dir . '/assets/css/mailchimp.css', array(), '');
		wp_enqueue_script('mh_mailchimp', get_stylesheet_directory_uri() . $mailchimp_dir . '/assets/js/mailchimp.js', array('jquery'), '', true);

	}

	public function set_parameters($form = 'mailchimp_form', $message = 'mc_message', $email_only = true){
		$this->form = $form;
		$this->message = $message;
		$this->email_only = $email_only;
	}

	public function get_mailchimp_template($formid, $msgid, $email_only, $list_id, $description){
		$lang = 'sl';
		$this->set_parameters($formid, $msgid, $email_only);

		if(function_exists('pll_current_language')){
			$lang = pll_current_language();
		}

		?>
        <form id="<?= $this->form; ?>">
        	<? if($description): ?>
        		<p class="xs-mt-20 xs-mb-20"><?php echo $description; ?></p>
        	<? endif; ?>
        	<? if( ! $this->email_only ): ?>
	            <div class="form-group">
	            	<div class="row">
	            		<div class="col-xs-12 col-sm-6">
		                	<input type="text" class="form-control text-center" name="mc_fname" placeholder="<?= __('First name', 'emigma'); ?>" />
		                </div>
		                <div class="col-xs-12 col-sm-6">
		                	<input type="text" class="form-control text-center" name="mc_lname" placeholder="<?= __('Last name', 'emigma'); ?>" />
						</div>
					</div>
				</div>
			<? endif; ?>
            <div class="form-group">
                <input type="email" class="form-control text-center" name="mc_email" placeholder="<? _e('Enter your e-mail', 'emigma'); ?>" />
                <input type="hidden" name="mc_lang" value="<?= $lang ?>" />
			</div>
            <div class="form-group">
            	<button type="button" class="btn btn-secondary" onclick="mh_mailchimp_subscribe('#<?= $this->form; ?>', '#<?= $this->message; ?>', '<?= $this->email_only; ?>', '<?php echo $list_id; ?>' )"><?= __('Subscribe', 'emigma') ?></button>
            </div>
            <div id="<?= $this->form; ?>-preloader" class="mailchimp-preloader"></div>
            <div class="form-group mailchimp-message">
            	<div id="<?= $this->message; ?>"></div>
            </div>
        </form>
        <?php
	}

	public function mh_mailchimp_app(){

		$vals = array();
		if(isset($_POST['vals'])){
			$vals = $_POST['vals'];
		}
		if(isset($_POST['email_only'])){
			$email_only = $_POST['email_only'];
		}
		if(isset($_POST['list_id'])){
			$list_id = $_POST['list_id'];
		}
		else{
			die(json_encode(array('error' => true, 'msg' => __('No values', 'emigma'))));
		}

		$response = $this->mh_mailchimp_subscribe($vals, $email_only, $list_id);
		die(json_encode($response));
	}

	public function mh_mailchimp_subscribe($vals, $email_only, $list_id){

		if( ! $this->api_key || ! $list_id ){
			return array(
				'error' => true,
				'msg' => __('No api key or list id.', 'emigma'),
			);
		}

		$pars = array();
		foreach($vals as $v){
			if($v['name'] == 'mc_email'){
				if(filter_var($v['value'], FILTER_VALIDATE_EMAIL) === false){
					return array('error' => true, 'msg' => __('E-mail not valid.', 'emigma'));
				}
			}
			$pars[$v['name']] = $v['value'];
		}

		$language['country_code'] = $pars['mc_lang'];

		$aParams = array(
            'email_address' => $pars['mc_email'],
            'location'    	=> $language,
            'language'		=> $language['country_code'],
            'status'        => 'subscribed',
		);

		if( ! $email_only ){
			$merge_fields['FNAME'] = $pars['mc_fname'];
			$merge_fields['LNAME'] = $pars['mc_lname'];
			$aParams['merge_fields'] = $merge_fields;
		}

		$MailChimp = new MailChimp($this->api_key);
		$result = $MailChimp->post("lists/$list_id/members", $aParams);

		if(isset($result['status']) && $result['status'] === 'subscribed'){
			$output = array(
				'error' => false,
				'msg' 	=> __('Subscribe successful.', 'emigma')
			);
		}elseif(isset($result['status']) && $result['status'] === 400){
			$info = $MailChimp->get('lists/'.$list_id.'/members/'.md5(strtolower($pars['mc_email'])));

			if(isset($info['status']) && $info['status'] === 'subscribed'){
				$output = array(
					'error' => false,
					'msg' 	=> sprintf( __('%s is already a list member.', 'emigma'), $pars['mc_email'] )
				);
			}else if(isset($info['status']) && $info['status'] !== 'subscribed'){
				$resubscribe = $MailChimp->put('lists/'.$list_id.'/members/'.md5(strtolower($pars['mc_email'])), array(
					'status' => 'subscribed',
				));
				if(isset($resubscribe['status']) && $resubscribe['status'] === 'subscribed'){
					$output = array(
						'error' => false,
						'msg' 	=> __('Subscribe successful.', 'emigma')
					);
				}else{
					$output = array(
						'error' => true,
						'msg' 	=> __('Unknown error.', 'emigma'),
						'resubscribe' => $resubscribe,
					);
				}
			}
		}else{
			$output = array(
				'error' => true,
				'msg' 	=> __('Error while subscribing.', 'emigma')
			);
		}

		return $output;
	}
}