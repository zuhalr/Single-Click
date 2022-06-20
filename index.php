<html>
<body>


<?php
$result = "";
$file = "untitle";
$code = "";
//proses setelah user klik submit
if (isset($_POST['submit'])) {
    //mengambil code dari user dan memisahkannya
    $code = $_POST['code']; 
    $code_split = explode(" ",$code);

    //menginisiasi variable yang akan digunakan
    $type = array();
    $variable_name=array();
    $type_number = 0;
    $variable_number = 0;
    $temp = "";
    $word = 0;
    $c = 0;
    $i = 0;
    $error_message = "";
    $error = FALSE;

    
    //mengambil kata kata kosong (terjadi ketika user menambahkan spasi di awal code)    
    while($code_split[$word] == ""){
        $word++;
    }

    //periksa apakah kata pertama adalah "create"
    $temp1 = strtoupper($code_split[$word]);
    if($temp1 != "CREATE"){
        $error = TRUE;
        $error_message = "Create tidak ditemukan " . $code_split[$word]. "?";
    }
    $word++;

    //jika kata pertama adalah create
    if($error == FALSE){
        $i = 0;
        
        //mengambil nama dari kelas yang akan dibuat (kata ke-2)
        $go = TRUE;
        while($go == TRUE && $i < strlen($code_split[$word])){
            if($code_split[$word][$i] != "(" ){
                $temp[$i] = $code_split[$word][$i];
                $i++;
            }else{
                $go = FALSE;
            }
        }
        //menyalin ke variable yang telah disiapkan
        $file_name = $temp;
        $file = $temp . ".java";
        $temp = "";

        //mengondisikan posisi kata atau huruf ke berapa untuk selanjutnya diproses (tidak bisa dipisah melalui spasi sehingga manual)
        if($i == strlen($code_split[$word])){
            
            $word++;
            $i = 0;
            if($code_split[$word][0] != "("){
                $error == TRUE;
                $error_message = "( tidak ditemukan";
            }else{
                if(strlen($code_split[$word]) == 1){
                    $word++;
                    $i = 0;
                }else{
                    $i = 1;
                }
                
            }
        }else if($code_split[$word][$i] == "("){
            if($i == strlen($code_split[$word])-1){
                $word++;
                $i = 0;
            }else{
                $i++;
            }
        }

        
        $j = 0;

        //mendapatkan tipe variabel pertama dari kelas
        for($i = $i; $i < strlen($code_split[$word]);$i++){
            $temp[$j++] = $code_split[$word][$i];
        }

        //periksa apakah tipe variabel tersebut merupakan int, char, dan lain-lain
        $temp1 = strtoupper($temp);
        if($temp1 != "INT" && $temp1 != "CHAR" && $temp != "double" && $temp != "String"&&$temp != "float" &&$temp != "Float" ){
            $error == TRUE;
            $error_message = "tipe variable tidak ditemukan " . $temp. "?";
        }else{
            array_push($type,$temp);
            $word++;
        }
        $next = TRUE;
        // mendapatkan nama variabel pertama dari kelas
        if($error == FALSE){
            $i = 0;
            $temp = "";
            $go = TRUE;
            //copy ke variabel temp
            while($go == TRUE && $i < strlen($code_split[$word])){
                if($code_split[$word][$i] != "," && $code_split[$word][$i] != ")"){
                    $temp[$i] = $code_split[$word][$i];
                    $i++;
                }else{
                    $go = FALSE;
                }
            }
            array_push($variable_name,$temp);
            //mengondisikan posisi kata atau huruf ke berapa untuk selanjutnya diproses (tidak bisa dipisah melalui spasi sehingga manual)
            if($i == strlen($code_split[$word])){
                $word++;
                $i = 0;
                if($code_split[$word][0] == ")"){
                    $next = FALSE;
                }else if($code_split[$word][0] == ","){
                    $word++;
                    $i = 0;
                }else{
                    $error = TRUE;
                    $next = FALSE;
                    $error_message = "code salah";
                }
            }else if($code_split[$word][$i] == ","){
                if($i == strlen($code_split[$word])-1){
                    $word++;
                    $i = 0;
                }else{
                    $i++;
                }
            }else if($code_split[$word][$i] == ")"){
                $next = FALSE;
                $i++;
            }
        }
        //proses untuk variabel ke dua dan seterusnya
        while($next == TRUE && $error == FALSE ){
            $temp = "";
            $j = 0;
            //mendapatkan tipe variabel
            for($i = $i; $i < strlen($code_split[$word]);$i++){
                $temp[$j++] = $code_split[$word][$i];
            }
            //periksa apakah sesuai dengan tipe variabel
            $temp1 = strtoupper($temp);
            if($temp1 != "INT" && $temp1 != "CHAR" && $temp != "double" && $temp != "String" &&$temp != "float" &&$temp != "Float"){
                $error == TRUE;
                $next = FALSE;
                $error_message = "tipe variable tidak ditemukan " . $temp. "?";
            }else{
                array_push($type,$temp);
                $word++;
            }

            //mendapatkan nama variabel
            if($error == FALSE){
                $i = 0;
                $temp = "";
                $go = TRUE;
                while($go == TRUE && $i < strlen($code_split[$word])){
                    if($code_split[$word][$i] != "," && $code_split[$word][$i] != ")"){
                        $temp[$i] = $code_split[$word][$i];
                        $i++;
                    }else{
                        $go = FALSE;
                    }
                }
                array_push($variable_name,$temp);
                $temp = "";
                
                if($i == strlen($code_split[$word])){
                    
                    $word++;
                    $i = 0;
                    echo $code_split[$word][0];
                    if($code_split[$word][0] == ")"){
                        $next = FALSE;
                        $i++;
                    }else if($code_split[$word][0] == ","){
                        $word++;
                        $i = 0;
                    }else{
                        $error = TRUE;
                        $error_message = "\",\" tidak ditemukan";
                    }
                }else if($code_split[$word][$i] == ","){
                    if($i == strlen($code_split[$word])-1){
                        $word++;
                        $i = 0;
                    }else{
                        $i++;
                    }
                }else if($code_split[$word][$i] == ")"){
                    $next = FALSE;
                    $i++;
                }
            }
        }
        echo "Nama Class   : ";
        print_r($file_name);
        //generate code
        $result = "";
        $init_var = "";
        $init_class = "";
        $set_var = "";
        $get_var = "";
        //periksa apakah code diakhiri dengan ';' atau tidak ada error(jika ada error tidak akan digenerate) 
        //periksa juga apakah ada ';' 
        if(isset($code_split[$word][$i])){
            if($code_split[$word][$i] == ";" && $error == FALSE){
                //generate sesuai dengan template kelas di java
                $init_class ="\n\t". $file_name. "(){}\n\n";
                $file_name = "public class $file_name{ \n";
                for($j = 0;$j < sizeof($type);$j++){
                }
                //generate sesuai dengan template kelas di java
                for($j = 0;$j < sizeof($type);$j++){
                    $init_var.= "\tpublic ". $type[$j]. " ". $variable_name[$j].";\n";
                    $set_var.= 
                    "\tpublic void set".$variable_name[$j]."(".$type[$j]." ". $variable_name[$j]."){\n\t\tthis.".$variable_name[$j]." = ".$variable_name[$j].";\n\t}\n\n";
                    $get_var.= 
                    "\tpublic ".$type[$j]." get".$variable_name[$j]."(){\n\t\treturn this.".$variable_name[$j].";\n\t}\n\n";
                }
                $result = $file_name .  $init_var . $init_class . $set_var . $get_var . "}";
    
            //jika ada error   
            }else if(strlen($error_message) > 0){
                $result = $error_message;
            //jika tidak diakhiri dengan ';'    
            }else if($code_split[$word][$i] != ";"){
                $result = "\";\" tidak ditemukan " . $code_split[$word][$i] . "?";
            }
        }else{
            $result = "\";\" tidak ditemukan "  ;
        }
        
    }
    if($error == TRUE){
        $result = $error_message;
        
    }
    echo "<br>";
    echo "Tipe Variabel  : ";
    print_r($type);
    echo "<br>";
    echo "Nama Variabel : ";
    print_r($variable_name);
}

//jika user mengklik download
//download akan keluar ketika user sudah mengisi code
if (isset($_POST['download'])) {
    //code akan ditulis sesuai dengan hasil dari code
    //nama file akan sesuai dengan kelas dari code
    $result = $_POST['result'];
    $file = $_POST['file'];
    $code = $_POST['code'];
    $myfile = fopen($file, "w") or die("Unable to open file!");
    fwrite($myfile, $result);
    fclose($myfile);
}

?>
<h1>Single Click</h1>

<!-- styling -->
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto;
  /* background-color: #2196F3; */
  padding: 10px;
}
.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 20px;
  font-size: 30px;
  text-align: center;
}
</style>

<!-- form code untuk user -->
<div class="grid-container">
  <div class="grid-item">
    <form method="POST"> 
    <textarea id="code" name="code" rows="30" cols="100"><?php if (isset($_POST['submit'])) { echo $code;} if (isset($_POST['download'])) { echo $code;}?> </textarea>
    <br>
    <input type="submit" value="Submit" name="submit">
    </form>
  </div>
  <div class="grid-item">
    <?php if (isset($_POST['submit'])) { ?>
        <form method="POST"> 
            <textarea id="codew" name="codew" rows="30" cols="100"><?php if (isset($_POST['submit'])) { echo $result;}?> </textarea>
            <input type="hidden" id="result" name="result" value="<?php if (isset($_POST['submit'])) { echo $result;}?>">
            <input type="hidden" id="file" name="file" value="<?php if (isset($_POST['submit'])) { echo $file;}?>">
            <input type="hidden" id="code" name="code" value="<?php if (isset($_POST['submit'])) { echo $code;}?>">
            <input type="submit" value="Download" name="download">
        </form>
        
        
    <?php }?>
    <?php if (isset($_POST['download'])) { ?>
    <form method="POST"> 
        <textarea id="code" name="codew" rows="30" cols="100"><?php if (isset($_POST['download'])) { echo $result;}?> </textarea>
        <input type="hidden" id="result" name="result" value="<?php if (isset($_POST['download'])) { echo $result;}?>">
        <input type="hidden" id="file" name="file" value="<?php if (isset($_POST['download'])) { echo $file;}?>">
        <input type="hidden" id="code" name="code" value="<?php if (isset($_POST['submit'])) { echo $code;}?>">
        <input type="submit" value="Download" name="download">
    </form>
    
    
<?php }?>
  </div> 
</div>

</body>
</html>
