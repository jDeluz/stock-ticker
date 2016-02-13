<?php
/**
 * Created by PhpStorm.
 * User: jondeluz
 * Date: 2016-02-10
 * Time: 3:41 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends Application {


    public function index()
    {
        $this->load->helper('form');

        $this->data['title'] = "Stocks";
        $this->data['left-panel-content']  = 'stock/index';
        $this->data['right-panel-content'] = 'templates/_footer';

        $stock = $this->stocks->getRecentStock();

        $form       = form_open('stock/display');
        $stock_codes  = array();
        $stock_names  = array();
        $stocks     = $this->stocks->getAllStocks();

        foreach( $stocks as $item )
        {
            array_push($stock_codes, $item->Code);
            array_push($stock_names, $item->Name);
        }

        $stocks = array_combine($stock_codes, $stock_names);


        $select                 = form_dropdown('stock',
                                                $stocks,
                                                $stock->Code,
                                                "class = 'form-control'" .
                                                "onchange = 'this.form.submit()'");

        $this->data['Name']     = $stock->Name;
        $this->data['Code']     = $stock->Code;
        $this->data['Category'] = $stock->Category;
        $this->data['Value']    = money_format("$%i", $stock->Value);

        $this->data['form']     = $form;
        $this->data['select']   = $select;

        //hokey
        $this->data['src'] = "../assets/js/stock-history.js";
        $this->render();
    }


    public function display()
    {
        $this->load->helper('form');

        if(!(empty($this->input->post('stock'))))
        {
            $code = $this->input->post('stock');
            $this->data['src'] = "../assets/js/stock-history.js";
        }
        else {
            $code = $this->uri->segment(3);
            $this->data['src'] = "../../assets/js/stock-history.js";
        }

        $this->data['title'] = "Stocks ~ $code";
        $this->data['left-panel-content'] = 'stock/index';
        $this->data['right-panel-content'] = 'templates/_footer';
        $this->data['stock_code'] = $code;

        $form        = form_open('stock/display');
        $stock_codes = array();
        $stock_names = array();
        $stocks      = $this->stocks->getAllStocks();
        $stock       = $this->stocks->get($code);

        foreach( $stocks as $item )
        {
            array_push($stock_codes, $item->Code);
            array_push($stock_names, $item->Name);
        }

        $stocks = array_combine($stock_codes, $stock_names);

        $select                 = form_dropdown('stock',
                                                $stocks,
                                                $code,
                                                "class = 'form-control'" .
                                                "onchange = 'this.form.submit()'");

        $this->data['Name']     = $stock->Name;
        $this->data['Code']     = $stock->Code;
        $this->data['Category'] = $stock->Category;
        $this->data['Value']    = money_format("$%i", $stock->Value);

        $this->data['form']     = $form;
        $this->data['select']   = $select;
        $this->render();
    }


}