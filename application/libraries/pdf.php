<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Chris Harvey
 * @license         MIT License
 * @link            https://github.com/chrisnharvey/CodeIgniter-  PDF-Generator-Library
 */

require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Pdf extends DOMPDF
{
    /**
     * Get an instance of CodeIgniter
     *
     * @access  protected
     * @return  void
     */
    protected function ci()
    {
        return get_instance();
    }

    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access  public
     * @param   string  $view The view to load
     * @param   array   $data The view data
     * @return  void
     */
    public function load_view($view, $data = array())
    {
        $dompdf = new Dompdf();
        $html = $this->ci()->load->view($view, $data, TRUE);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();
        $time = time();

        // Output the generated PDF to Browser
        $dompdf->stream("welcome-" . $time);
    }
    // function createPDF($html, $filename = '', $download = TRUE, $save_flag = false, $paper = 'A4', $orientation = 'portrait')
    /*Change Orientataion by varsha for silo Health Patient Pdf on 30 Dec*/
    /*if orientation then page set default A4 and portrait and if true the set height and width*/
    function createPDF($html, $filename = '', $download = TRUE, $save_flag = false, $orientation = false,$folder_path='')

    {
        $dompdf = new DOMPDF();
        /*added by varsha for show img in pdf on 30 dec*/
        $dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->load_html($html);
        if($orientation){
            $dompdf->set_paper(array(0,0,900,1600)); 
        }else{
            $dompdf->set_paper('A4', 'portrait');
        }
        $dompdf->render();
        $canvas = $dompdf->get_canvas();
        $footer = $canvas->open_object();
        $w = $canvas->get_width();
        $h = $canvas->get_height();
           
        $canvas->page_text($w - 60, $h - 15, "Page {PAGE_NUM} of {PAGE_COUNT}", 'helvetica', 6);
        $canvas->page_text($w - 590, $h - 15, "Powered By Silocloud", 'helvetica', 10);
        if(!$orientation){
        $canvas->page_text($w - 380, $h - 840, $filename." Documentation", 'helvetica', 15);
        }

        $canvas->close_object();
        $canvas->add_object($footer, "all");

        $output = $dompdf->output();

        if ($save_flag) {
            $upload_path = 'uploads/pdf';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }
            file_put_contents(FCPATH . 'uploads/pdf/' . $filename . ".pdf", $output);
            return base_url() . 'uploads/pdf/' . $filename . ".pdf";
        }
        
         if (!empty($folder_path)) {
            $upload_path_health = $folder_path;
            if (!is_dir($upload_path_health)) {
                mkdir($upload_path_health, 0777, TRUE);
            }
            file_put_contents(FCPATH . $folder_path . $filename . ".pdf", $output);
            return base_url() . $folder_path . $filename . ".pdf";
        }

        if ($download)
            $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename . '.pdf', array('Attachment' => 0));
    }
}