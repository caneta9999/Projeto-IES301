export function navbar() {
    const navbar = document.getElementById("navbar");

    navbar.innerHTML = "<header style='background-color: #3dbdbe;'>" 
        + "<a href='/projeto-ies301/paginas/index.php' id='nomeProjeto'>Projeto IES301</a>"
        + "<form id='formLogout' method='POST' action='/projeto-ies301/paginas/Login/logout.php'>"
        + "<input id='inputSubmit' name='sairSubmit' type='submit' value='Sair da conta'>"
        + "</form>"
        + "</header>"
    return navbar;
}

