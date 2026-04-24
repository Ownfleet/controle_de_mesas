<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Mesas Disponíveis</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(160deg, #111, #1f1f1f, #000);
    color: white;
    min-height: 100vh;
}

header {
    background: linear-gradient(135deg, #ff7a00, #ff3d00);
    padding: 24px 18px;
    text-align: center;
    border-bottom-left-radius: 28px;
    border-bottom-right-radius: 28px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.45);
}

header h1 {
    margin: 0;
    font-size: 30px;
    font-weight: 900;
}

header p {
    margin: 8px 0 0;
    font-size: 15px;
    opacity: 0.95;
}

.container {
    padding: 18px;
    max-width: 900px;
    margin: auto;
}

.alerta {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 18px;
    padding: 14px;
    margin-bottom: 16px;
    font-size: 14px;
    line-height: 1.4;
}

.btn-som {
    width: 100%;
    border: none;
    border-radius: 18px;
    padding: 17px;
    font-size: 18px;
    font-weight: 900;
    background: linear-gradient(135deg, #ffb000, #ff6a00);
    color: #111;
    box-shadow: 0 8px 18px rgba(255,106,0,0.35);
    margin-bottom: 18px;
    cursor: pointer;
}

.resumo {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}

.card-resumo {
    background: rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 13px;
    text-align: center;
}

.card-resumo strong {
    display: block;
    font-size: 24px;
}

.card-resumo span {
    font-size: 12px;
    color: #ccc;
}

.grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px;
}

.mesa {
    border-radius: 22px;
    padding: 22px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 92px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.45);
}

.mesa-numero {
    font-size: 34px;
    font-weight: 900;
}

.mesa-status {
    font-size: 19px;
    font-weight: 900;
    text-align: right;
}

.disponivel {
    background: linear-gradient(135deg, #158000, #33b000);
    animation: pulse 1.2s infinite;
}

.ocupada {
    background: linear-gradient(135deg, #b00000, #ff0000);
}

.fechada {
    background: linear-gradient(135deg, #333, #111);
    color: #aaa;
}

.bolinha {
    display: inline-block;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    margin-right: 8px;
    vertical-align: middle;
}

.disponivel .bolinha {
    background: #6cff8d;
    box-shadow: 0 0 14px #6cff8d;
}

.ocupada .bolinha {
    background: #ff6b6b;
    box-shadow: 0 0 14px #ff6b6b;
}

.fechada .bolinha {
    background: #888;
}

.atualizacao {
    text-align: center;
    margin: 18px 0;
    color: #ccc;
    font-size: 13px;
}

.erro {
    background: #400;
    color: #fff;
    padding: 15px;
    border-radius: 16px;
    text-align: center;
    margin-bottom: 15px;
    display: none;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.025); }
    100% { transform: scale(1); }
}

@media (min-width: 650px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
</head>

<body>

<header>
    <h1>MESAS DISPONÍVEIS</h1>
    <p>Acompanhe em tempo real qual mesa está livre para carregamento</p>
</header>

<div class="container">

    <div class="alerta">
        ⚠️ Para receber aviso sonoro quando uma mesa ficar livre, clique no botão abaixo uma vez.
    </div>

    <button class="btn-som" onclick="ativarSom()">🔊 ATIVAR ALERTA SONORO</button>

    <div class="erro" id="erro"></div>

    <div class="resumo">
        <div class="card-resumo">
            <strong id="qtdDisponivel">0</strong>
            <span>Disponíveis</span>
        </div>
        <div class="card-resumo">
            <strong id="qtdOcupada">0</strong>
            <span>Ocupadas</span>
        </div>
        <div class="card-resumo">
            <strong id="qtdFechada">0</strong>
            <span>Fechadas</span>
        </div>
    </div>

    <div class="grid" id="listaMesas"></div>

    <div class="atualizacao" id="atualizacao">
        Atualizando...
    </div>

</div>

<audio id="somAlerta" preload="auto">
    <source src="https://actions.google.com/sounds/v1/alarms/alarm_clock.ogg" type="audio/ogg">
</audio>

<script>
let somAtivo = false;
let disponiveisAntes = [];

function ativarSom() {
    somAtivo = true;

    const audio = document.getElementById("somAlerta");
    audio.volume = 1;
    audio.play().catch(() => {});

    alert("Alerta sonoro ativado com sucesso!");
}

async function carregarMesas() {
    try {
        const resposta = await fetch("api.php?t=" + Date.now());
        const dados = await resposta.json();

        if (dados.erro) {
            mostrarErro(dados.mensagem);
            return;
        }

        document.getElementById("erro").style.display = "none";

        const lista = document.getElementById("listaMesas");
        lista.innerHTML = "";

        let qtdDisponivel = 0;
        let qtdOcupada = 0;
        let qtdFechada = 0;

        const disponiveisAgora = [];

        dados.mesas.forEach(mesa => {
            if (mesa.tipo === "disponivel") {
                qtdDisponivel++;
                disponiveisAgora.push(mesa.numero);
            }

            if (mesa.tipo === "ocupada") qtdOcupada++;
            if (mesa.tipo === "fechada") qtdFechada++;

            const card = document.createElement("div");
            card.className = "mesa " + mesa.tipo;

            let statusTexto = "OCUPADA";

            if (mesa.tipo === "disponivel") statusTexto = "DISPONÍVEL";
            if (mesa.tipo === "fechada") statusTexto = "FECHADA";

            card.innerHTML = `
                <div class="mesa-numero">MESA ${mesa.numero}</div>
                <div class="mesa-status">
                    <span class="bolinha"></span>${statusTexto}
                </div>
            `;

            lista.appendChild(card);
        });

        document.getElementById("qtdDisponivel").innerText = qtdDisponivel;
        document.getElementById("qtdOcupada").innerText = qtdOcupada;
        document.getElementById("qtdFechada").innerText = qtdFechada;

        const novaLivre = disponiveisAgora.some(numero => !disponiveisAntes.includes(numero));

        if (novaLivre && somAtivo) {
            tocarSom();
        }

        disponiveisAntes = disponiveisAgora;

        document.getElementById("atualizacao").innerText =
            "Última atualização: " + new Date().toLocaleTimeString("pt-BR");

    } catch (e) {
        mostrarErro("Erro ao atualizar as mesas.");
    }
}

function tocarSom() {
    const audio = document.getElementById("somAlerta");
    audio.volume = 1;
    audio.currentTime = 0;
    audio.play().catch(() => {});
}

function mostrarErro(msg) {
    const erro = document.getElementById("erro");
    erro.innerText = msg;
    erro.style.display = "block";
}

carregarMesas();
setInterval(carregarMesas, 5000);
</script>

</body>
</html>