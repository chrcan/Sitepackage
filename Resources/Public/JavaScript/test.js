// Funktion zum Generieren von 6 Zufallszahlen zwischen 1 und 49
function generateLottoNumbers() {
  let lottoNumbers = [];

  while (lottoNumbers.length < 6) {
    let randomNumber = Math.floor(Math.random() * 49) + 1;

    if (lottoNumbers.indexOf(randomNumber) === -1) {
      lottoNumbers.push(randomNumber);
    }
  }

  return lottoNumbers;
}

// Diese Funktion validiert eine E-Mail-Adresse. Es verwendet eine reguläre Ausdruck, um zu überprüfen, ob die E-Mail-Adresse dem gültigen Format entspricht. Wenn die E-Mail-Adresse gültig ist, gibt die Funktion true zurück, andernfalls false.


function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}