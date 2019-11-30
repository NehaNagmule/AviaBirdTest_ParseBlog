<?php 

function parseTextFile($file){
    if( !$file = file_get_contents($file))
        throw new Exception('No file was found!!');
    $data = [];
    $tags = [];
    $firstLine = true;
    $dash_cnt = 0;
    $file_arr = explode("\n", $file);
    $content = '';
    $flag = false;
    foreach($file_arr as $key => $line) {
        if(startsWith($line,"---") == TRUE){
        	$dash_cnt++;
        }
        if(startsWith($line,"---") == FALSE){
        	if(startsWith($line,"preview_image") == FALSE && startsWith($line,"section") == FALSE  && $dash_cnt < 2){
        		$line_arr=explode(':', $line);
        		if (strpos($line_arr[1], ',') !== false) {
    				$arr_tags = explode(',', $line_arr[1]);
    				foreach ($arr_tags as $value) {
	    				array_push($tags, $value);
    				}
        			$data[$line_arr[0]] = $tags;
				}else{
					$data[$line_arr[0]] = $line_arr[1];
				}
        		
        	}
        }

        if(isset($file_arr[$key+1])){
			if(startsWith($file_arr[$key+1],"READMORE") == TRUE){
        		$data["short-content"] = $file_arr[$key-1];
        	}
		}


		if(startsWith($line,"READMORE") == TRUE){
        		$flag = true;
        }

		if($flag == true){
			if(startsWith($line,"READMORE") == FALSE){
				$content .= $line . ' ';
			}
		}
        
    }

    $data["content"] = $content;

    /*print_r(json_encode($data,true));*/
    return json_encode($data,true);
}

function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 


$filename = "sample_blog.txt";
$parsed_data =  parseTextFile($filename);
$res = json_decode($parsed_data,true);
print_r($res);
?>