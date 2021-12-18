<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	private function load()
	{
		// $this->output->cache(1);

		$page = array(
			"head" => $this->load->view('website/template/head', false, true),
			"header" => $this->load->view('website/template/header', false, true),
			"main_js" => $this->load->view('website/template/main_js', false, true),
			"preloader" => $this->load->view('website/template/preloader', false, true),
			"footer" => $this->load->view('website/template/footer', false, true)
		);
		return $page;
	}

	public function index()
	{
		// $this->output->cache(1);
		
		$corona = 'https://picodep.depok.go.id/api';
        $corona = json_decode(file_get_contents($corona), true);
        $v['corona'] = $corona;

		$cuaca = $this->cuaca();
        $v['temperatur_hariini'] = $this->celcius($cuaca['temperature_current']);
        $v['temperature_besok_rendah'] = $this->celcius($cuaca['temperature_besok_rendah']);
        $v['temperature_besok_tinggi'] = $this->celcius($cuaca['temperature_besok_tinggi']);
        $v['temperature_lusa_rendah'] = $this->celcius($cuaca['temperature_lusa_rendah']);
        $v['temperature_lusa_tinggi'] = $this->celcius($cuaca['temperature_lusa_tinggi']);
        $v['icon_cuaca'] = ['hari_ini' => $this->get_icon($cuaca['icon']['hari_ini']) , 'besok' => $this->get_icon($cuaca['icon']['besok']) , 'lusa' => $this->get_icon($cuaca['icon']['lusa']) , ];

		$path = "";
		$data = array(
			"page" => $this->load($path),
			"content" => $this->load->view('website/index', $v, true)
		);
		$this->load->view('website/template/default_template', $data);
	}

	    //=========================================================================//
    //GET API CUACA DRAKSKY UNTUK DIKIRIM KE INDEX
    public function cuaca() {
        $coordinates = '-6.3944475, 106.8213664';

        $api_url = 'https://api.darksky.net/forecast/760b82e261c5be16214234e316b05fd8/' . $coordinates;
        $forecast = json_decode(file_get_contents($api_url));

        $temperature_current = round($forecast
            ->currently
            ->temperature);
        $icon['hari_ini'] = $forecast
            ->currently->icon;
        $icon['besok'] = $forecast
            ->daily
            ->data[1]->icon;
        $icon['lusa'] = $forecast
            ->daily
            ->data[2]->icon;
        $result['temperature_current'] = $temperature_current;
        $result['temperature_besok_rendah'] = $forecast
            ->daily
            ->data[1]->temperatureLow;
        $result['temperature_besok_tinggi'] = $forecast
            ->daily
            ->data[1]->temperatureHigh;
        $result['temperature_lusa_tinggi'] = $forecast
            ->daily
            ->data[2]->temperatureHigh;
        $result['temperature_lusa_rendah'] = $forecast
            ->daily
            ->data[2]->temperatureLow;
        $result['icon'] = $icon;
        return $result;
    }
    //MENGHITUNG SUHU DALAM CELCIUS
    public function celcius($temperature_current) {
        return round(($temperature_current - 32) * .5556);
    }
    //SETTING ICON CUACA SESUAI DENGAN DATA DARI API
    public function get_icon($icon) {
        if ($icon === 'clear-day')
        {
            $the_icon = '<i class="wi wi-day-sunny"></i>';
            return $the_icon;
        }
        elseif ($icon === 'clear-night')
        {
            $the_icon = '<i class="wi wi-night-clear"></i>';
            return $the_icon;
        }
        elseif ($icon === 'rain')
        {
            $the_icon = '<i class="wi wi-rain"></i>';
            return $the_icon;
        }
        elseif ($icon === 'snow')
        {
            $the_icon = '<i class="wi wi-snow"></i>';
            return $the_icon;
        }
        elseif ($icon === 'sleet')
        {
            $the_icon = '<i class="wi wi-sleet"></i>';
            return $the_icon;
        }
        elseif ($icon === 'wind')
        {
            $the_icon = '<i class="wi wi-strong-wind"></i>';
            return $the_icon;
        }
        elseif ($icon === 'fog')
        {
            $the_icon = '<i class="wi wi-fog"></i>';
            return $the_icon;
        }
        elseif ($icon === 'cloudy')
        {
            $the_icon = '<i class="wi wi-cloudy"></i>';
            return $the_icon;
        }
        elseif ($icon === 'partly-cloudy-day')
        {
            $the_icon = '<i class="wi wi-day-sunny-overcast"></i>';
            return $the_icon;
        }
        elseif ($icon === 'partly-cloudy-night')
        {
            $the_icon = '<i class="wi wi-night-alt-partly-cloudy"></i>';
            return $the_icon;
        }
        else
        {
            $the_icon = '<i class="wi wi-thermometer"></i>';
        }
    }
    //=========================================================================//

}
