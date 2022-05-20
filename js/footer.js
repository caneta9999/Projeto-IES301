export function footer() {
    const footer = document.getElementById("footer");
    footer.innerHTML = "<footer>"
        + "<p style='margin-bottom: 0px;'>2022 - Projeto de Laboratório de Engenharia de Software (IES301) </p>"
        + "<a href='http://www.fatecsp.br' target=_blank rel='noopener' style='color: white; text-decoration: underline'>Fatec São Paulo</a>"
         + "</footer>"
    return footer;
}