<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.
	
	These are taken from comments fields or ADIF fields 
*/

class Awards extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		// Render Page
		$data['page_title'] = "Awards";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/index');
		$this->load->view('interface_assets/footer');
	}
	
	public function dok ()
	{
		//echo "Needs Developed";
		$this->load->model('dok');
		$data['doks'] = $this->dok->show_stats();
		$data['worked_bands'] = $this->dok->get_worked_bands();

		// Render Page
		$data['page_title'] = "Awards - DOK";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/dok/index');
		$this->load->view('interface_assets/footer');

	}
	
	public function dok_details(){
        $a = $this->input->get();
        $q = "";
        foreach ($a as $key => $value) {
        	$q .= $key."=".$value.("&#40;and&#41;");
        }
        $q = substr($q, 0, strlen($q)-13);

        $arguments["query"] = $q;
        $arguments["fields"] = '';
        $arguments["format"] = "json";
        $arguments["limit"] = '';
        $arguments["order"] = '';

        // print_r($arguments);
        // return;

		// Load the API and Logbook models
		$this->load->model('api_model');
		$this->load->model('logbook_model');

		// Call the parser within the API model to build the query
		$query = $this->api_model->select_parse($arguments);

		// Execute the query, and retrieve the results
		$data = $this->logbook_model->api_search_query($query);

		// Render Page
		$data['page_title'] = "Log View - DOK";
		$data['filter'] = str_replace("&#40;and&#41;", ", ", $q);//implode(", ", array_keys($a));
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/dok/details');
		$this->load->view('interface_assets/footer');
	}
	
	public function dxcc ()	{
		//echo "Needs Developed";
		$this->load->model('dxcc');
		$data['dxcc'] = $this->dxcc->show_stats();
		$data['worked_bands'] = $this->dxcc->get_worked_bands();

		// Render Page
		$data['page_title'] = "Awards - DXCC";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/dxcc/index');
		$this->load->view('interface_assets/footer');

	}

	public function dxcc_details(){
 
		$this->load->model('logbook_model');

		$country = str_replace('"', "", $this->input->get("Country"));
		$band = str_replace('"', "", $this->input->get("Band"));
		$data['results'] = $this->logbook_model->dxcc_qso_details($country, $band);

				// Render Page
		$data['page_title'] = "Log View - DXCC";
		$data['filter'] = "country ".$country. " and ".$band;
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/dxcc/details');
		$this->load->view('interface_assets/footer');
	}

	/*
		Handles Displaying of WAB Squares worked.
		Comment field - WAB:#
	*/
	public function wab() {
	
		// Grab all worked WABs
		$this->load->model('wab');
		$data['wab_all'] = $this->wab->get_all();
	
		// Render Page
		$data['page_title'] = "Awards - WAB";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/wab/index');
		$this->load->view('interface_assets/footer');
	}
	
	/*
		Handles showing worked SOTAs
		Comment field - SOTA:#
	*/
	public function sota() {
	
		// Grab all worked sota stations
		$this->load->model('sota');
		$data['sota_all'] = $this->sota->get_all();
	
		// Render page
		$data['page_title'] = "Awards - SOTA";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/sota/index');
		$this->load->view('interface_assets/footer');
	}
	
	/*
		Handles showing worked WACRAL members (wacral.org)
		Comment field - WACRAL:#
	*/
	public function wacral() {
	
		// Grab all worked wacral members
		$this->load->model('wacral');
		$data['wacral_all'] = $this->wacral->get_all();
	
		// Render page
		$data['page_title'] = "Awards - WACRAL Members";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/wacral/index');
		$this->load->view('interface_assets/footer');
	}
	
	public function cq(){
        $this->load->model('cq');
        $zones = array();
        foreach($this->cq->get_zones() as $row){
            array_push($zones, intval($row->COL_CQZ));
        }
        $data['cqz'] = $zones;

        // Render page
        $data['page_title'] = "Awards - CQ Magazine";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/cq/index');
		$this->load->view('interface_assets/footer');
	}

    public function cq_details(){
        $this->load->model('logbook_model');

        $cqzone = str_replace('"', "", $this->input->get("CQZone"));
        $data['results'] = $this->logbook_model->cq_qso_details($cqzone);

        // Render Page
        $data['page_title'] = "Log View - DXCC";
        $data['filter'] = "CQZone ".$cqzone;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/cq/details');
        $this->load->view('interface_assets/footer');
    }

    public function was() {
        $this->load->model('was');
        $data['was'] = $this->was->show_stats();
        $data['worked_bands'] = $this->was->get_worked_bands();

        // Render Page
        $data['page_title'] = "Awards - WAS (Worked all states)";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/was/index');
        $this->load->view('interface_assets/footer');
    }

    public function was_details() {
        $this->load->model('logbook_model');

        $state = str_replace('"', "", $this->input->get("State"));
        $band = str_replace('"', "", $this->input->get("Band"));
        $data['results'] = $this->logbook_model->was_qso_details($state, $band);

        // Render Page
        $data['page_title'] = "Log View - WAS";
        $data['filter'] = "state ".$state. " and ".$band;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/was/details');
        $this->load->view('interface_assets/footer');
    }

}