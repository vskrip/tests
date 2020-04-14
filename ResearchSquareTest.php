<?php

///////////////////////////////////////////////////////////////////////////////
//
// RESEARCH SQUARE CODING EXERCISE
//
// In this exercise, we would like you to write PHP code capable of solving the
// problems below. To begin, please make a local copy of this file and open it
// using your favorite code editor.
//
// We have designed this exercise to mimic the types of problems we solve on a
// daily basis. It is intended for engineers who are proficient in PHP and we
// expect it will take approximately 30 minutes to complete. The code you write
// will be used solely for evaluation purposes.
//
// During the exercise, you may look for help on Google, php.net, and other
// publicly available resources. However, please do *NOT* ask your friends or
// colleagues for help. We want to see *your* solution.
//
// When you are finished, please paste your solution in the questionnaire form
// OR provide a link to your solution using a tool like GitHub Gist. We will
// run your code on the command line using PHP 7.4.
//
// Your solution will be evaluated using the following criteria:
// - Does it output the correct results?
// - Are there any potential bugs or defects?
// - Is the code pragmatic and easy to understand?
// - Does it continue to work when new inputs are provided?
//
// Good luck!
//
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
//
// TASK #1
//
// Summary:
//
//   The data file below contains a list of customers and their orders. Use
//   these data to find and print the price of the most expensive order.
//
//   Hint: Open the data file and look at its contents. The `total_price` field
//   holds the price of an order.
//
// Expected output:
//
//   Most expensive order = 500.00
//
///////////////////////////////////////////////////////////////////////////////

// url with source data in json format
$url = 'https://rs-coding-exercise.s3.amazonaws.com/2020/orders-2020-02-10.json';
// get and decode data
$response = json_decode(file_get_contents($url), true);

if(!empty($response))
{
    unset($customers);
    unset($orders);
    unset($total_prices);

    // getting an array of customers from json object
    $customers = (empty($response['customers'])) ? array() : $response['customers'];
    // getting an array of orders from json object
    $orders = (empty($response['orders'])) ? array() : $response['orders'];

    // init the total prices array
    $total_prices = array();
    
    // getting total prices of all orders and pushing to the total prices array
    foreach($orders as $order)
    {
        $total_prices[] = $order['total_price'];
    }
}

echo('<pre>');
print_r("\nMost expensive order = ");
print_r(max($total_prices));

///////////////////////////////////////////////////////////////////////////////
//
// TASK #2
//
// Summary:
//
//   Using this same data file, calculate and print the sum of prices for all
//   orders created in the previous three years, grouped by year.
//
// Expected output:
//
//   Total price of orders in 2018 = 275.00
//   Total price of orders in 2019 = 860.00
//   Total price of orders in 2020 =  20.00
//
///////////////////////////////////////////////////////////////////////////////

unset($order_created_date);
unset($order_created_year);
unset($total_price_orders_2018);
unset($total_price_orders_2019);
unset($total_price_orders_2020);

$total_price_orders_2018 = 0;
$total_price_orders_2019 = 0;
$total_price_orders_2020 = 0;

foreach($orders as $order)
{    
    $total_price_order = (!empty($order['total_price'])) ? $order['total_price'] : 0;
    $order_created_date = (!empty($order['created_date'])) ? $order['created_date'] : "";
    $order_created_datetime = (!empty($order_created_date)) ? strtotime($order_created_date) : "";
    $order_created_year = (!empty($order_created_datetime)) ? date("Y", $order_created_datetime) : "";

    // counting order's total prices by years
    if($order_created_year == 2018)
    {
        $total_price_orders_2018 += $total_price_order;
    }
    elseif($order_created_year == 2019)
    {
        $total_price_orders_2019 += $total_price_order;
    }
    elseif($order_created_year == 2020)
    {
        $total_price_orders_2020 += $total_price_order;        
    }    
}

print_r("\nTotal price of orders in 2018 =");
print_r($total_price_orders_2018);
print_r("\nTotal price of orders in 2019 =");
print_r($total_price_orders_2019);
print_r("\nTotal price of orders in 2020 =");
print_r($total_price_orders_2020);



///////////////////////////////////////////////////////////////////////////////
//
// TASK #3
//
// Summary:
//
//   Using the same data file, find and print the ID and name of the customer
//   with the most orders.
//
// Expected output:
//
//   Customer with the most orders = [CUST-0001] Yoda
//
///////////////////////////////////////////////////////////////////////////////


unset($customer_name);
unset($customer_orders_count_array);
$customer_orders_count_array = array();

// loop by customers array
foreach($customers as $key=>$customer)
{
    // initialize order's counter
    $orders_counter = 0;

    // get customer id and name from customers array
    $customer_id = (!empty($customer['id'])) ? $customer['id'] : "";
    $customer_name = (!empty($customer['name'])) ? $customer['name'] : "";
    // loop by orders array
    foreach($orders as $order)
    {  
        // get customer id from the order
        $order_customer_id = (!empty($order['customer_id'])) ? $order['customer_id'] : "";
        // increment order counter for right customer
        if($customer_id == $order_customer_id)
        {
            $orders_counter++;
        }
    }
    // push data to new created array
    $customer_orders_count_array[$key]['customer_id'] = $customer_id;
    $customer_orders_count_array[$key]['name'] = $customer_name;
    $customer_orders_count_array[$key]['total_orders_count'] = $orders_counter;
}

// processing new array and leave only customer with maximum value of order counter
$customer_orders_count_array = array_reduce($customer_orders_count_array, function ($a, $b) {
    return @$a['total_orders_count'] > $b['total_orders_count'] ? $a : $b ;
});

print_r("\nCustomer with the most orders = ");
print_r("[" . $customer_orders_count_array['customer_id'] . "]" . " " . $customer_orders_count_array['name']);
exit;
