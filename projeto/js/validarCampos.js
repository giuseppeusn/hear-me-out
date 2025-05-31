function formataCampo(campo, Mascara, evento) {
  let boleanoMascara;
  const Digitato = evento.keyCode;
  exp = /\-|\.|\/|\(|\)| /g;
  campoSoNumeros = campo.value.toString().replace(exp, "");
  let posicaoCampo = 0;
  let NovoValorCampo = "";
  let TamanhoMascara = campoSoNumeros.length;
  if (Digitato != 8) {
    for (i = 0; i <= TamanhoMascara; i++) {
      boleanoMascara =
        Mascara.charAt(i) == "-" ||
        Mascara.charAt(i) == "." ||
        Mascara.charAt(i) == "/";
      boleanoMascara =
        boleanoMascara ||
        Mascara.charAt(i) == "(" ||
        Mascara.charAt(i) == ")" ||
        Mascara.charAt(i) == " ";
      if (boleanoMascara) {
        NovoValorCampo += Mascara.charAt(i);
        TamanhoMascara++;
      } else {
        NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
        posicaoCampo++;
      }
    }
    campo.value = NovoValorCampo;
    return true;
  } else {
    return true;
  }
}

function mascaraInteiro() {
  if (event.keyCode < 48 || event.keyCode > 57) {
    event.returnValue = false;
    return false;
  }
  return true;
}

function MascaraCPF(cpf) {
  if (mascaraInteiro(cpf) == false) {
    event.returnValue = false;
  }
  return formataCampo(cpf, "000.000.000-00", event);
}
