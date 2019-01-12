<?php
/**
 * 
 */
require_once 'config.php';

class ClientAPI
{
	// show users accound details

	function show_accounts($instance_url, $access_token) {
	    $query = "SELECT Name, Id from Account LIMIT 100";
	    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER,
	            array("Authorization: OAuth $access_token"));

	    $json_response = curl_exec($curl);
	    curl_close($curl);

	    $response = json_decode($json_response, true);

	    $total_size = $response['totalSize'];

	    echo "$total_size record(s) returned<br/><br/>";
	    foreach ((array) $response['records'] as $record) {
	        echo $record['Id'] . ", " . $record['Name'] . "<br/>";
	    }
	    echo "<br/>";
	}

	// create or register new user in salesforce

	function create_account($data, $instance_url, $access_token) {
	    $url = "$instance_url/services/data/v20.0/sobjects/Account/";

	    $content = json_encode(array("Name" => $data['name'],"Phone" => $data['phone'],"Website" => $data['website'],
	    "Email__c" => $data['email'],"Bedrooms__c" => $data['bedrooms']));

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER,
	            array("Authorization: OAuth $access_token",
	                "Content-type: application/json"));
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

	    $json_response = curl_exec($curl);

	    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    if ( $status != 201 ) {
	    	$result = array('error' => curl_error($curl),'status' => $status, 'response' => $json_response,'curl_no' => curl_errno($curl));
	    	echo json_encode($result); exit;
	        // die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	    }
	    
	    // echo "HTTP status $status creating account<br/><br/>";

	    curl_close($curl);

	    $response = json_decode($json_response, true);

	    $result['status'] = "OK";
	    $result['code'] = $status;
	    $result['message'] = "User created successfully";
	    $result['response'] = $response["id"];

	    echo json_encode($result);

	    // return $id;
	}

	// show perticular account details of salesforce account
	function show_account($id, $instance_url, $access_token) {
	    $url = "$instance_url/services/data/v20.0/sobjects/Account/$id";

	    // print_r('show account' . $url); exit;

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER,
	            array("Authorization: OAuth $access_token"));

	    $json_response = curl_exec($curl);

	    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    if ( $status != 200 ) {
	        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	    }

	    echo "HTTP status $status reading account<br/><br/>";

	    curl_close($curl);

	    $response = json_decode($json_response, true);

	    foreach ((array) $response as $key => $value) {
	        echo "$key:$value<br/>";
	    }
	    echo "<br/>";
	}

	// update account details
	function update_account($id, $data, $instance_url, $access_token) {
	    $url = "$instance_url/services/data/v20.0/sobjects/Account/$id";

	    $content = json_encode(array("Name" => $data['name'],"Phone" => $data['phone'],"Website" => $data['website'],
	    "Email__c" => $data['email'],"Bedrooms__c" => $data['bedrooms']));

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_HTTPHEADER,
	            array("Authorization: OAuth $access_token",
	                "Content-type: application/json"));
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

	    $json_response = curl_exec($curl);

	    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    if ( $status != 204 ) {
	        // die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	        $result = array('error' => curl_error($curl),'status' => $status, 'response' => $json_response,'curl_no' => curl_errno($curl));
	        echo json_encode($result); die;
	    }

	    // echo "HTTP status $status updating account<br/><br/>";
	    curl_close($curl);

	    $response = json_decode($json_response, true);

	    $result['status'] = "OK";
	    $result['update'] = "OK";
	    $result['code'] = $status;
	    $result['message'] = "User updated successfully";
	    $result['response'] = $response;

	    echo json_encode($result); die;
	}

	// delete account details

	function delete_account($id, $instance_url, $access_token) {
	    $url = "$instance_url/services/data/v20.0/sobjects/Account/$id";

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_HTTPHEADER,
	            array("Authorization: OAuth $access_token"));
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

	    curl_exec($curl);

	    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    if ( $status != 204 ) {
	        die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	    }

	    echo "HTTP status $status deleting account<br/><br/>";

	    curl_close($curl);
	}
}