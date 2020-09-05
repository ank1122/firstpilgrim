<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Import extends CI_Controller {
    // construct
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('Import_model', 'import');
        $this->load->helper(array('url','html','form'));
    }    
 
    public function index() {
        $this->load->view('import');
    }

    
    public function importFile(){
  
      if ($this->input->post('submit')) {
                 
                $path = 'uploads/';
                require_once APPPATH . "/third_party/PHPExcel.php";
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'xlsx|xls|csv';
                $config['remove_spaces'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);            
                if (!$this->upload->do_upload('uploadFile')) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    $data = array('upload_data' => $this->upload->data());
                }
                if(empty($error)){
                  if (!empty($data['upload_data']['file_name'])) {
                    $import_xls_file = $data['upload_data']['file_name'];
                } else {
                    $import_xls_file = 0;
                }
                $inputFileName = $path . $import_xls_file;
                 
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $flag = true;
                    $i=0;
                    foreach ($allDataInSheet as $value) {
                      if($flag){
                        $flag =false;
                        continue;
                      }
                      $inserdata[$i]['serial_number'] = $value['A'];
                      $inserdata[$i]['trail_name'] = $value['B'];
                      $inserdata[$i]['trail_northeast'] = $value['C'];
                      $inserdata[$i]['trail_southwest'] = $value['D'];
                      $inserdata[$i]['description'] = $value['E'];
                      $inserdata[$i]['min_altitude'] = $value['F'];
                      $inserdata[$i]['max_altitude'] = $value['G'];
                      $inserdata[$i]['area'] = $value['H'];
                      $inserdata[$i]['district'] = $value['I'];
                      $inserdata[$i]['state'] = $value['J'];
                      $inserdata[$i]['country'] = $value['K'];
                      $inserdata[$i]['difficulty_id'] = $value['L'];
                      $inserdata[$i]['days'] = $value['M'];
                      $inserdata[$i]['duration'] = $value['N'];
                      $inserdata[$i]['minutes'] = $value['O'];
                      $inserdata[$i]['distance'] = $value['P'];
                      $inserdata[$i]['country_two'] = $value['Q'];
                      $inserdata[$i]['continent'] = $value['R'];
                      $inserdata[$i]['base_camp_name'] = $value['S'];
                      $inserdata[$i]['base_camp_latitude'] = $value['T'];
                      $inserdata[$i]['base_camp_longitude'] = $value['U'];
                      $inserdata[$i]['base_camp_altitude'] = $value['V'];
                      $inserdata[$i]['terminal_point_one_name'] = $value['W'];
                      $inserdata[$i]['terminal_point_one_lat'] = $value['X'];
                      $inserdata[$i]['terminal_point_one_long'] = $value['Y'];
                      $inserdata[$i]['terminal_point_one_alt'] = $value['Z'];
                      $inserdata[$i]['is_base_camp_terminal_point_one'] = $value['AA'];
                      $inserdata[$i]['terminal_point_two_name'] = $value['AB'];
                      $inserdata[$i]['terminal_piont_two_lat'] = $value['AC'];
                      $inserdata[$i]['terminal_point_two_long'] = $value['AD'];
                      $inserdata[$i]['terminal_point_two_alt'] = $value['AE'];
                      $inserdata[$i]['is_base_camp_terminal_point_two'] = $value['AF'];
                      $inserdata[$i]['nearest_town'] = $value['AG'];
                      $inserdata[$i]['nearest_town_lat'] = $value['AH'];
                      $inserdata[$i]['nearest_town_long'] = $value['AI'];
                      $inserdata[$i]['nearest_town_alt'] = $value['AJ'];
                      $inserdata[$i]['atm_available'] = $value['AK'];
                      $inserdata[$i]['cellphone_reception'] = $value['AL'];
                      $inserdata[$i]['instructions'] = $value['AM'];
                      $inserdata[$i]['mobile_internet'] = $value['AN'];
                      $inserdata[$i]['permit_required'] = $value['AO'];
                      $inserdata[$i]['permit_types_accepted'] = $value['AP'];
                      $inserdata[$i]['max_altitude_place_name'] = $value['AQ'];
                      $inserdata[$i]['min_altitude_place_name'] = $value['AR'];
                      $inserdata[$i]['trail_season'] = $value['AS'];
                      $inserdata[$i]['trail_month'] = $value['AT'];
                      $inserdata[$i]['trail_popularity'] = $value['AU'];
                      $inserdata[$i]['trail_highlight'] = $value['AV'];
                      $inserdata[$i]['trail_type'] = $value['AW'];
                      $inserdata[$i]['trail_image_file_1'] = $value['AX'];
                      $inserdata[$i]['trail_image_file_2'] = $value['AY'];
                      $inserdata[$i]['trail_image_file_3'] = $value['AZ'];
                      $inserdata[$i]['trail_image_file_4'] = $value['BA'];
                      $inserdata[$i]['trail_image_file_5'] = $value['BB'];
                      $inserdata[$i]['GpxFile'] = $value['BC'];
                      $i++;
                    }
                    
                    if($value['C'] & $value['D'] == NULL){
                        echo "Trail". $value['B'] ."was not uploaded";}
                    else{
                    $result = $this->import->insert($inserdata);   }
                    if($result){
                      echo "Imported successfully";
                    }else{
                      echo "ERROR !";
                    }             
      
              } catch (Exception $e) {
                   die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                            . '": ' .$e->getMessage());
                }
              }else{
                  echo $error['error'];
                }
                 
                 
        }
        $this->load->view('import');
        
    }
     
}
?>