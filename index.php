<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Mesas Disponíveis</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    box-sizing:border-box;
    -webkit-tap-highlight-color:transparent;
}

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:
        radial-gradient(circle at top left, rgba(255,122,0,.22), transparent 35%),
        radial-gradient(circle at bottom right, rgba(0,255,120,.12), transparent 30%),
        linear-gradient(160deg,#080808,#161616,#000);
    color:white;
    min-height:100vh;
}

header{
    padding:26px 18px 22px;
    text-align:center;
    background:linear-gradient(135deg,#ff7a00,#ff3d00);
    border-bottom-left-radius:32px;
    border-bottom-right-radius:32px;
    box-shadow:0 12px 35px rgba(0,0,0,.45);
}

header h1{
    margin:0;
    font-size:30px;
    font-weight:900;
    letter-spacing:.5px;
}

header p{
    margin:8px 0 0;
    font-size:14px;
    opacity:.95;
}

.container{
    width:100%;
    max-width:950px;
    margin:0 auto;
    padding:18px;
}

.alerta{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.14);
    backdrop-filter:blur(12px);
    border-radius:20px;
    padding:15px;
    margin-bottom:14px;
    font-size:14px;
    line-height:1.45;
    box-shadow:0 10px 25px rgba(0,0,0,.25);
}

.btn-som{
    width:100%;
    border:none;
    border-radius:20px;
    padding:17px;
    font-size:18px;
    font-weight:900;
    background:linear-gradient(135deg,#ffcf33,#ff7a00);
    color:#151515;
    box-shadow:0 10px 24px rgba(255,106,0,.35);
    margin-bottom:16px;
    cursor:pointer;
}

.btn-som.ativo{
    background:linear-gradient(135deg,#35ff7a,#15b84a);
    color:#061006;
}

.resumo{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    margin-bottom:18px;
}

.card-resumo{
    background:rgba(255,255,255,.09);
    border:1px solid rgba(255,255,255,.12);
    border-radius:18px;
    padding:14px 8px;
    text-align:center;
    box-shadow:0 8px 22px rgba(0,0,0,.25);
}

.card-resumo strong{
    display:block;
    font-size:27px;
    font-weight:900;
}

.card-resumo span{
    display:block;
    margin-top:3px;
    font-size:12px;
    color:#d0d0d0;
}

.grid{
    display:grid;
    grid-template-columns:1fr;
    gap:14px;
}

.mesa{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    padding:22px 18px;
    min-height:96px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 12px 28px rgba(0,0,0,.42);
    border:1px solid rgba(255,255,255,.16);
}

.mesa::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,rgba(255,255,255,.18),transparent 38%);
    pointer-events:none;
}

.mesa-numero{
    position:relative;
    z-index:1;
    font-size:33px;
    font-weight:900;
}

.mesa-status{
    position:relative;
    z-index:1;
    font-size:18px;
    font-weight:900;
    text-align:right;
}

.disponivel{
    background:linear-gradient(135deg,#058f2b,#19c653);
    animation:pulse 1.3s infinite;
}

.ocupada{
    background:linear-gradient(135deg,#b50000,#ff2b2b);
}

.fechada{
    background:linear-gradient(135deg,#333,#111);
    color:#bdbdbd;
}

.mesa.liberada-agora{
    outline:4px solid #fff;
    animation:liberou .8s infinite alternate;
}

.bolinha{
    display:inline-block;
    width:17px;
    height:17px;
    border-radius:50%;
    margin-right:8px;
    vertical-align:middle;
}

.disponivel .bolinha{
    background:#7dff9f;
    box-shadow:0 0 18px #7dff9f;
}

.ocupada .bolinha{
    background:#ff9b9b;
    box-shadow:0 0 18px #ff6b6b;
}

.fechada .bolinha{
    background:#999;
}

.atualizacao{
    text-align:center;
    margin:18px 0;
    color:#cfcfcf;
    font-size:13px;
}

.erro{
    background:#5a0000;
    color:#fff;
    padding:15px;
    border-radius:16px;
    text-align:center;
    margin-bottom:15px;
    display:none;
}

.toast{
    position:fixed;
    left:50%;
    top:18px;
    transform:translateX(-50%) translateY(-130%);
    width:calc(100% - 28px);
    max-width:520px;
    background:linear-gradient(135deg,#ffffff,#eaffef);
    color:#06250d;
    border-radius:22px;
    padding:18px;
    z-index:9999;
    box-shadow:0 16px 35px rgba(0,0,0,.45);
    text-align:center;
    font-weight:900;
    transition:.35s ease;
}

.toast.show{
    transform:translateX(-50%) translateY(0);
}

.toast .titulo{
    font-size:20px;
}

.toast .sub{
    margin-top:5px;
    font-size:14px;
    color:#1d5a2a;
}

@keyframes pulse{
    0%{transform:scale(1)}
    50%{transform:scale(1.025)}
    100%{transform:scale(1)}
}

@keyframes liberou{
    from{filter:brightness(1)}
    to{filter:brightness(1.35)}
}

@media(min-width:650px){
    .grid{
        grid-template-columns:repeat(2,1fr);
    }
}
</style>
</head>

<body>

<div class="toast" id="toast">
    <div class="titulo" id="toastTitulo">Mesa liberada</div>
    <div class="sub" id="toastSub">Aguarde...</div>
</div>

<header>
    <h1>MESAS DISPONÍVEIS</h1>
    <p>Acompanhe em tempo real qual mesa está livre para carregamento</p>
</header>

<div class="container">

    <div class="alerta">
        ⚠️ Clique em <strong>ATIVAR ALERTA SONORO</strong>. Quando uma mesa liberar, o sistema vai mostrar a notificação e falar qual mesa ficou disponível.
    </div>

    <button class="btn-som" id="btnSom" onclick="ativarSom()">🔊 ATIVAR ALERTA SONORO</button>

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
    <source src="https://actions.google.com/sounds/v1/notifications/notification_simple-02.ogg" type="audio/ogg">
</audio>

<script>
let somAtivo = false;
let disponiveisAntes = [];
let primeiraCarga = true;
let toastTimer = null;

function ativarSom(){
    somAtivo = true;

    const btn = document.getElementById("btnSom");
    btn.classList.add("ativo");
    btn.innerText = "✅ ALERTA SONORO ATIVADO";

    const audio = document.getElementById("somAlerta");
    audio.volume = 0.6;
    audio.play().catch(()=>{});

    falarTexto("Alerta ativado");
    mostrarNotificacao("✅ Alerta ativado", "Agora o sistema vai avisar quando uma mesa liberar.");
}

async function carregarMesas(){
    try{
        const resposta = await fetch("api.php?t=" + Date.now());
        const dados = await resposta.json();

        if(dados.erro){
            mostrarErro(dados.mensagem || "Erro ao buscar mesas.");
            return;
        }

        document.getElementById("erro").style.display = "none";

        const lista = document.getElementById("listaMesas");
        lista.innerHTML = "";

        let qtdDisponivel = 0;
        let qtdOcupada = 0;
        let qtdFechada = 0;

        const disponiveisAgora = [];

        dados.mesas.forEach(mesa=>{
            if(mesa.tipo === "disponivel"){
                qtdDisponivel++;
                disponiveisAgora.push(mesa.numero);
            }

            if(mesa.tipo === "ocupada") qtdOcupada++;
            if(mesa.tipo === "fechada") qtdFechada++;

            const card = document.createElement("div");
            card.className = "mesa " + mesa.tipo;
            card.dataset.mesa = mesa.numero;

            let statusTexto = "OCUPADA";

            if(mesa.tipo === "disponivel") statusTexto = "DISPONÍVEL";
            if(mesa.tipo === "fechada") statusTexto = "FECHADA";

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

        const mesasNovasLiberadas = disponiveisAgora.filter(
            numero => !disponiveisAntes.includes(numero)
        );

        if(!primeiraCarga && mesasNovasLiberadas.length > 0){
            destacarMesas(mesasNovasLiberadas);
            notificarMesasLiberadas(mesasNovasLiberadas);

            if(somAtivo){
                tocarSom();
                falarMesasLiberadas(mesasNovasLiberadas);
            }
        }

        disponiveisAntes = disponiveisAgora;
        primeiraCarga = false;

        document.getElementById("atualizacao").innerText =
            "Última atualização: " + new Date().toLocaleTimeString("pt-BR");

    }catch(e){
        mostrarErro("Erro ao atualizar as mesas.");
    }
}

function tocarSom(){
    const audio = document.getElementById("somAlerta");
    audio.volume = 0.6;
    audio.currentTime = 0;
    audio.play().catch(()=>{});
}

function falarMesasLiberadas(mesas){
    const texto = mesas.length === 1
        ? `Atenção, mesa ${mesas[0]} disponível`
        : `Atenção, mesas ${mesas.join(", ")} disponíveis`;

    falarTexto(texto);
}

function falarTexto(texto){
    if(!("speechSynthesis" in window)) return;

    window.speechSynthesis.cancel();

    const msg = new SpeechSynthesisUtterance(texto);
    msg.lang = "pt-BR";
    msg.rate = 0.95;
    msg.pitch = 1;
    msg.volume = 1;

    window.speechSynthesis.speak(msg);
}

function notificarMesasLiberadas(mesas){
    const titulo = mesas.length === 1
        ? `🟢 MESA ${mesas[0]} LIBERADA`
        : `🟢 MESAS ${mesas.join(", ")} LIBERADAS`;

    const sub = mesas.length === 1
        ? `A mesa ${mesas[0]} está disponível para carregamento.`
        : `As mesas ${mesas.join(", ")} estão disponíveis para carregamento.`;

    mostrarNotificacao(titulo, sub);
}

function mostrarNotificacao(titulo, sub){
    const toast = document.getElementById("toast");
    document.getElementById("toastTitulo").innerText = titulo;
    document.getElementById("toastSub").innerText = sub;

    toast.classList.add("show");

    clearTimeout(toastTimer);
    toastTimer = setTimeout(()=>{
        toast.classList.remove("show");
    }, 5500);
}

function destacarMesas(mesas){
    mesas.forEach(numero=>{
        setTimeout(()=>{
            const card = document.querySelector(`.mesa[data-mesa="${numero}"]`);
            if(card){
                card.classList.add("liberada-agora");
                setTimeout(()=>{
                    card.classList.remove("liberada-agora");
                }, 7000);
            }
        }, 100);
    });
}

function mostrarErro(msg){
    const erro = document.getElementById("erro");
    erro.innerText = msg;
    erro.style.display = "block";
}

carregarMesas();
setInterval(carregarMesas, 5000);
</script>

</body>
</html>