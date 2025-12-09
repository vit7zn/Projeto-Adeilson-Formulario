<?php
session_start();
include('verifica_login.php');
include('conexao.php');

// ========== CARD 1: Total de alunos ==========
$sqlTotal = "SELECT COUNT(*) AS total FROM alunos";
$totalAlunos = mysqli_fetch_assoc(mysqli_query($conexao, $sqlTotal))['total'];

// ========== CARD 2: Total de cursos ==========
$sqlTotalCursos = "SELECT COUNT(DISTINCT curso) AS total FROM alunos";
$totalCursos = mysqli_fetch_assoc(mysqli_query($conexao, $sqlTotalCursos))['total'];

// ========== CARD 3: Total de tipos de responsáveis ==========
$sqlTotalResponsaveis = "SELECT COUNT(DISTINCT tipo) AS total FROM alunos";
$totalResponsaveis = mysqli_fetch_assoc(mysqli_query($conexao, $sqlTotalResponsaveis))['total'];

// ========== CARD 4: Total de bairros ==========
$sqlTotalBairros = "SELECT COUNT(DISTINCT bairro) AS total FROM alunos";
$totalBairros = mysqli_fetch_assoc(mysqli_query($conexao, $sqlTotalBairros))['total'];

// ========== GRÁFICO 1: Alunos por Curso ==========
$sqlCursos = "SELECT curso, COUNT(*) AS total FROM alunos GROUP BY curso";
$resultCursos = mysqli_query($conexao, $sqlCursos);
$cursos = [];
$totaisCursos = [];
while ($row = mysqli_fetch_assoc($resultCursos)) {
    $cursos[] = $row['curso'];
    $totaisCursos[] = $row['total'];
}

// ========== GRÁFICO 2: Tipos de Responsáveis ==========
$sqlTipos = "SELECT tipo, COUNT(*) AS total FROM alunos GROUP BY tipo";
$resultTipos = mysqli_query($conexao, $sqlTipos);
$tipos = [];
$totaisTipos = [];
while ($row = mysqli_fetch_assoc($resultTipos)) {
    $tipos[] = $row['tipo'];
    $totaisTipos[] = $row['total'];
}

// ========== GRÁFICO 3: Alunos por Bairro ==========
$sqlBairros = "SELECT bairro, COUNT(*) AS total FROM alunos GROUP BY bairro";
$resultBairros = mysqli_query($conexao, $sqlBairros);
$bairros = [];
$totaisBairros = [];
while ($row = mysqli_fetch_assoc($resultBairros)) {
    $bairros[] = $row['bairro'];
    $totaisBairros[] = $row['total'];
}

// ========== GRÁFICO 4: Idade dos Alunos ==========
$sqlIdades = "SELECT data_nascimento FROM alunos";
$resultIdades = mysqli_query($conexao, $sqlIdades);
$idades = [];
while ($row = mysqli_fetch_assoc($resultIdades)) {
    $idade = date_diff(date_create($row['data_nascimento']), date_create('today'))->y;
    $idades[] = $idade;
}

// Frequência por idade
$contadorIdades = array_count_values($idades);
$labelsIdades = array_keys($contadorIdades);
$totaisIdades = array_values($contadorIdades);

// ========== GRÁFICO 5: Cadastros por mês ==========
$sqlMeses = "
    SELECT MONTH(data_nascimento) AS mes, COUNT(*) AS total
    FROM alunos
    GROUP BY MONTH(data_nascimento)
";
$resultMeses = mysqli_query($conexao, $sqlMeses);
$meses = [];
$totaisMeses = [];
$nomesMeses = [1=>'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

while ($row = mysqli_fetch_assoc($resultMeses)) {
    $meses[] = $nomesMeses[$row['mes']];
    $totaisMeses[] = $row['total'];
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Completo</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        .contador {
            font-size: 40px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">SISTEMA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="painel.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="formulario.php">Formulário</a></li>
        <li class="nav-item"><a class="nav-link" href="painel.php">Gráficos</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

    <!-- CARDS ANIMADOS -->
    <div class="row text-center mb-4">

        <div class="col-md-3">
            <div class="card text-bg-primary p-3">
                <h5>Total de Alunos</h5>
                <div class="contador" id="c1">0</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-success p-3">
                <h5>Cursos Cadastrados</h5>
                <div class="contador" id="c2">0</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-warning p-3">
                <h5>Tipos de Responsável</h5>
                <div class="contador" id="c3">0</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-danger p-3">
                <h5>Bairros</h5>
                <div class="contador" id="c4">0</div>
            </div>
        </div>

    </div>

    <!-- GRÁFICOS -->
    <div class="row g-4">

        <div class="col-md-6"><div class="card p-3"><h5 class="text-center">Alunos por Curso</h5><canvas id="g1"></canvas></div></div>

        <div class="col-md-6"><div class="card p-3"><h5 class="text-center">Responsáveis</h5><canvas id="g2"></canvas></div></div>

        <div class="col-md-6"><div class="card p-3"><h5 class="text-center">Alunos por Bairro</h5><canvas id="g3"></canvas></div></div>

        <div class="col-md-6"><div class="card p-3"><h5 class="text-center">Idade dos Alunos</h5><canvas id="g4"></canvas></div></div>

        <div class="col-md-12"><div class="card p-3"><h5 class="text-center">Cadastros por Mês</h5><canvas id="g5"></canvas></div></div>

    </div>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ----- Dados PHP → JS -----
const cursos = <?= json_encode($cursos) ?>;
const totaisCursos = <?= json_encode($totaisCursos) ?>;

const tipos = <?= json_encode($tipos) ?>;
const totaisTipos = <?= json_encode($totaisTipos) ?>;

const bairros = <?= json_encode($bairros) ?>;
const totaisBairros = <?= json_encode($totaisBairros) ?>;

const labelsIdades = <?= json_encode($labelsIdades) ?>;
const totaisIdades = <?= json_encode($totaisIdades) ?>;

const meses = <?= json_encode($meses) ?>;
const totaisMeses = <?= json_encode($totaisMeses) ?>;

// ----- Contadores animados -----
function animar(id, valorFinal) {
    let cont = 0;
    let intervalo = setInterval(() => {
        cont++;
        document.getElementById(id).innerText = cont;
        if (cont >= valorFinal) clearInterval(intervalo);
    }, 20);
}

animar("c1", <?= $totalAlunos ?>);
animar("c2", <?= $totalCursos ?>);
animar("c3", <?= $totalResponsaveis ?>);
animar("c4", <?= $totalBairros ?>);

// ----- Gráficos -----

new Chart(document.getElementById('g1'), {
    type: 'bar',
    data: { labels: cursos, datasets: [{ label: "Alunos", data: totaisCursos }] }
});

new Chart(document.getElementById('g2'), {
    type: 'pie',
    data: { labels: tipos, datasets: [{ data: totaisTipos }] }
});

new Chart(document.getElementById('g3'), {
    type: 'bar',
    data: { labels: bairros, datasets: [{ label: "Alunos", data: totaisBairros }] }
});

new Chart(document.getElementById('g4'), {
    type: 'line',
    data: { labels: labelsIdades, datasets: [{ label: "Alunos", data: totaisIdades }] }
});

new Chart(document.getElementById('g5'), {
    type: 'bar',
    data: { labels: meses, datasets: [{ label: "Cadastros", data: totaisMeses }] }
});
</script>

</body>
</html>
