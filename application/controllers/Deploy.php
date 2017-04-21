<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Deploy extends CI_Controller {
	private $data_debug;
	private $data_result;
	// php /var/www/codeigniter/3.0.6/tvbox/index.php deploy receive
	public function receive() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'github' );
			// 變數
			$data_input = array ();
			$data_config = array ();
			// 接收變數
			$data_input ['key'] = $this->input->post ( 'key' );
			$data_input ['payload'] = $this->input->post ( 'payload' );
			$data_config ['clone'] = $this->config->item ( 'github_clone_https' );
			$data_config ['key'] = $this->config->item ( 'secret_key' );
			$data_config ['path'] = $this->config->item ( 'directory_path' );
			$data_config ['log'] = $this->config->item ( 'webhooks_log' );
			$this->data_result ['data_input'] = $data_input;
			$this->data_result ['data_config'] = $data_config;
			$this->data_result ['GET'] = $_GET;
			$this->data_result ['POST'] = $_POST;
			$this->data_result ['SERVER'] = $_SERVER;
			if (! empty ( $data_input ['key'] ) && ! empty ( $data_input ['payload'] ) && $data_input ['key'] == $data_config ['key']) {
				$payload = json_decode ( $data_input ['payload'], true );
				if ($payload ['ref'] == 'refs/heads/master') {
					$command = sprintf ( '/usr/bin/git -C %s pull 2>&1', $data_config ['path'] );
					exec ( $command, $output, $status );
					$this->data_result ['message'] = sprintf ( '執行git指令:[%s],執行解果:%s', $command, $status, js );
				} else {
					$this->data_result ['message'] = sprintf ( '執行git指令失敗ref值錯誤:[%s]', $payload ['ref'] );
				}
			} else {
				$this->data_result ['message'] = sprintf ( '執行git指令失敗key值錯誤:[%s]', $data_input ['key'] );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			return $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
