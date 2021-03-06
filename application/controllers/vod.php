<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Vod extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->view->layout( 'vod/layout' );
		$this->view->js( array(
				'vod/index',
			) );
		$this->view->css( array(
				'vod/index',
			) );
		$this->view->cache( 10 );
	}

	public function _remap( $method = 'index', $params = array() ) {
		$this->view->title_routes( array(
				'index'    => '隨選視訊影片',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	public function index( $type = NULL, $roll = NULL ) {

		$this->load->library( 'core/media' );
		$this->load->library( 'core/storage' );
		$this->load->model( 'Model_vod' );

		// 取論壇精彩vod推薦
		$_vod_list = $this->storage->get( array(
			'cache_name' => 'vod---index',
			'callback'   => array( $this->Model_vod, 'get_vod' ),
			'params'     => array( 'limit' => 16 ),
		) );

		if ( ! empty( $type ) and ! empty( $roll ) ) {
			$this->view->data['playing'] = array(
				array(
					'first_video_type' => $type,
					'first_video_code' => $roll,
				)
			);
		}
		else {
			$this->view->data['playing'] = array_slice( $_vod_list, 0, 1 );
		}
		
		// 其他推薦
		$this->view->data["videos_right_list"]  = array_slice( $_vod_list, 0, 4 );
		$this->view->data["videos_bottom_list"] = array_slice( $_vod_list, 4, 1000 );
	}
}

//
