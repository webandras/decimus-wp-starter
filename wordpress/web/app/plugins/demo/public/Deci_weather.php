<?php

!defined( ABSPATH ) || die();

if ( !class_exists( 'Deci_weather' ) ) {


    final class Deci_weather
    {
        // Open Weather API properties
        private const API_KEY = '6bd5b850178e2134497c4b965fbaf54e'; // this is not my api key
        private const CURRENT_URL = 'https://api.openweathermap.org/data/2.5/weather';
        private const LANG = 'en';
        private const UNITS = 'metric';


        // Transient to cache the requested weather data with a timeout
        private const TRANSIENT_NAME = 'deci-demo-weather-data';


        // Used for weather-icons.css
        private const icon_mapping = [
            '01d' => 'wi-day-sunny',
            '01n' => 'wi-night-clear',
            '02d' => 'wi-day-cloudy',
            '02n' => 'wi-night-alt-cloudy',
            '03d' => 'wi-cloud',
            '03n' => 'wi-night-alt-cloudy',
            '04d' => 'wi-cloudy',
            '04n' => 'wi-night-alt-cloudy',
            '09d' => 'wi-rain',
            '09n' => 'wi-night-alt-rain',
            '10d' => 'wi-day-rain',
            '10n' => 'wi-night-alt-rain',
            '11d' => 'wi-thunderstorm',
            '11n' => 'wi-night-alt-thunderstorm',
            '13d' => 'wi-snow',
            '13n' => 'wi-night-alt-snow',
            '50d' => 'wi-fog',
            '50n' => 'wi-night-alt-fog'
        ];


        private array $params;
        private string $city_name;


        public function __construct()
        {
            $this->city_name = 'Szeged,hu';

            // Query parameters
            $this->params = [
                'q' => $this->city_name,
                'appid' => self::API_KEY,
                'units' => self::UNITS,
                'lang' => self::LANG,
            ];
        }


        /**
         * Current weather shortcode that is using the Open Weather API
         * Usage: [current_weather color=primary city=Szeged]
         **/
        public function get_current_weather_shortcode($attr): string
        {
            extract( $attr );

            // load frontend assets
            wp_enqueue_style( 'demo-weather-icons', plugins_url() . '/demo/public/assets/css/weather-icons.css',
                array(),
                false, 'all' );

            // escaping for usage
            $color = esc_attr( $color ) ?? 'light';
            $city = esc_html( $city ) ?? 'Szeged';

            $weather_transient = get_transient( self::TRANSIENT_NAME );

            // no transient -> fetch weather data from API
            if ( $weather_transient === false ) {

                $results = $this->get_current_weather();

                // return the error message to the frontend
                if ( $results['error'] ) {
                    return $results['error'];
                }
                $data = $results['data'];

                set_transient( self::TRANSIENT_NAME, $data, 60 );

            } else {
                $data = $weather_transient;
            }


            $weather_data = $data->main->temp . '°C, '
                . $data->main->pressure . ' hPa, '
                . $data->main->humidity . '%, '
                . $data->wind->speed . ' km/h';

            $weather_icon = self::icon_mapping[$data->weather[0]->icon];

            // weather content
            $html = '<div class="demo-border">';
            $html .= '<div class="demo-badge badge bg-' . $color . '">' . sprintf( __( 'The weather in %s: ',
                    'deci-demo' ),
                    $city );
            $html .= '&nbsp<i class="wi ' . esc_attr( $weather_icon ) . '"></i>&nbsp';
            $html .= '</div>';

            $html .= '<p>' . $data->weather[0]->description . '<br>( ' . $weather_data . ' )</p>';
            $html .= '</div>';

            return $html;
        }


        /** Fetch weather data from API */
        private function get_current_weather(): array
        {
            $results = [
                'error' => false,
                'data' => null
            ];

            $url = $this->_construct_query();

            try {
                $response = wp_remote_get( $url );
                $response_code = (int) wp_remote_retrieve_response_code( $response );
                // $response_message = wp_remote_retrieve_response_message($response); // empty if there is an error
                // $data = wp_remote_retrieve_body($response);  // empty if there is an error

                // wp_remote_get error
                if ( $response_code !== 200 && $response instanceof WP_Error ) {
                    throw new Exception( $response->errors["http_request_failed"][0], $response_code );
                }

                $data = json_decode( $response['body'] );

                // Open Weather API error
                if ( $data->cod !== 200 ) {
                    throw new Exception( $data->message, $data->cod );
                }

                $results['data'] = $data;

            } catch ( Exception $e ) {
                $results['error'] = '<p class="alert alert-danger">Current Weather Shortcode: ' . $e->getMessage() . '</p>';
            }

            return $results;

        }


        /** Construct the query url */
        private function _construct_query(): string
        {
            $query = self::CURRENT_URL . '?';
            $values = array_values( $this->params );
            $keys = array_keys( $this->params );
            $max = sizeof( $this->params );

            for ( $i = 0; $i < $max; $i++ ) {
                $param = $keys[$i] . '=' . $values[$i];
                $query .= ( $i < $max - 1 ) ? $param . '&' : $param;
            }
            return $query;
        }

    }

    $deci_weather = new Deci_weather;

}
