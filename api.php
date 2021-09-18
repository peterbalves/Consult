<?php

// CODE BY HYDRA

error_reporting(0);

$CPF = $_POST['consultacpf'];

function GetStr($str, $start, $end) {
  $a = explode($end, explode($start, $str)[1] )[0];
  return $a;
}

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'http://www.juventudeweb.mte.gov.br/pnpepesquisas.asp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,  'acao=consultar%20cpf&cpf='.$CPF.'&nocache=0.7636039437638835');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml, application/x-www-form-urlencoded;charset=ISO-8859-1, text/xml; charset=ISO-8859-1','Cookie: ASPSESSIONIDSCCRRTSA=NGOIJMMDEIMAPDACNIEDFBID; FGTServer=2A56DE837DA99704910F47A454B42D1A8CCF150E0874FDE491A399A5EF5657BC0CF03A1EEB1C685B4C118A83F971F6198A78','Host: www.juventudeweb.mte.gov.br']);
$ConsultaCPF = curl_exec($ch);

//echo $ConsultaCPF;

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

if(stristr($ConsultaCPF, 'NOPESSOAFISICA') != false)
    $CPF = GetStr($ConsultaCPF, 'NRCPF="', '"');
    $Nome = GetStr($ConsultaCPF, 'NOPESSOAFISICA="', '"');
    $DataNascimento = GetStr($ConsultaCPF, 'DTNASCIMENTO="', '"');
    $DataNascimento = explode('/', $DataNascimento);
    $DataNascimento = $DataNascimento[0] . '/' . $DataNascimento[1] . '/' . $DataNascimento[2];
    $NomeMae = GetStr($ConsultaCPF, 'NOMAE="', '"');
    $Rua = GetStr($ConsultaCPF, 'NOLOGRADOURO="', '"');
    $NumeroRua = GetStr($ConsultaCPF, 'NRLOGRADOURO="', '"');
    $Complemento = GetStr($ConsultaCPF, 'DSCOMPLEMENTO="', '"');
    $Bairro = GetStr($ConsultaCPF, 'NOBAIRRO="', '"');
    $CEP = GetStr($ConsultaCPF, 'NRCEP="', '"');
    $Cidade = GetStr($ConsultaCPF, 'NOMUNICIPIO="', '"');
    $EstadoSigla = GetStr($ConsultaCPF, 'SGUF="', '"');
    $Estado = GetStr($ConsultaCPF, 'SGUF="', '"');

switch ($Estado) {
    case 'AC': $Estado = 'Acre';
        break;
    case 'AL': $Estado = 'Alagoas';
        break;
    case 'AP': $Estado = 'Amapá';
        break;
    case 'AM': $Estado = 'Amazonas';
        break;
    case 'BA': $Estado = 'Bahia';
        break;
    case 'CE': $Estado = 'Ceará';
        break;
    case 'DF': $Estado = 'Distrito Federal';
        break;
    case 'ES': $Estado = 'Espírito Santo';
        break;
    case 'GO': $Estado = 'Goiás';
        break;
    case 'MA': $Estado = 'Maranhão';
        break;
    case 'MT': $Estado = 'Mato Grosso';
        break;
    case 'MS': $Estado = 'Mato Grosso do Sul';
        break;
    case 'MG': $Estado = 'Minas Gerais';
        break;
    case 'PA': $Estado = 'Pará';
        break;
    case 'PB': $Estado = 'Paraíba';
        break;
    case 'PR': $Estado = 'Paraná';
        break;
    case 'PE': $Estado = 'Pernambuco';
        break;
    case 'PI': $Estado = 'Piauí';
        break;
    case 'RJ': $Estado = 'Rio de Janeiro';
        break;
    case 'RN': $Estado = 'Rio Grande do Norte';
        break;
    case 'RS': $Estado = 'Rio Grande do Sul';
        break;
    case 'RO': $Estado = 'Rondônia';
        break;
    case 'SC': $Estado = 'Santa Catarina';
        break;
    case 'SP': $Estado = 'São Paulo';
        break;
    case 'SE': $Estado = 'Sergipe';
        break;
    case 'TO': $Estado = 'Tocantins';
        break;
}

$Nome = mb_convert_case($Nome, MB_CASE_TITLE, 'UTF-8');
$NomeMae = mb_convert_case($NomeMae, MB_CASE_TITLE, 'UTF-8');
$Rua = mb_convert_case($Rua, MB_CASE_TITLE, 'UTF-8');
$Complemento = mb_convert_case($Complemento, MB_CASE_TITLE, 'UTF-8');
$Bairro = mb_convert_case($Bairro, MB_CASE_TITLE, 'UTF-8');
$Cidade = mb_convert_case($Cidade, MB_CASE_TITLE, 'UTF-8');
$Estado = mb_convert_case($Estado, MB_CASE_TITLE, 'UTF-8');


$DadosConsulta = [
    "Code" => 200,
    "Dados" => [
        "CPF" => "$CPF",
        "Nome" => "$Nome",
        "NomeMae" => "$NomeMae",
        "DataNascimento" => "$DataNascimento",
        "Rua" => "$Rua",
        "NumeroRua" => "$NumeroRua",
        "Complemento" => "$Complemento",
        "Bairro" => "$Bairro",
        "CEP" => "$CEP",
        "Cidade" => "$Cidade",
        "Estado" => "$Estado",
        "EstadoSigla" => "$EstadoSigla",
        "Pais" => "Brasil"]
];

$DadosConsultaTwo = [
    "Code" => 500,
    "Retorno" => "Error!"
];

$DadosConsultaTree = [
    "Code" => 404,
    "Retorno" => "CPF Inválido!"
];

header('Content-type: text/javascript');

if(strpos($ConsultaCPF, 'NOPESSOAFISICA')){
    echo json_encode($DadosConsulta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

}else if($ConsultaCPF == null){
    echo json_encode($DadosConsultaTwo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

}else{
    echo json_encode($DadosConsultaTree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

?>
