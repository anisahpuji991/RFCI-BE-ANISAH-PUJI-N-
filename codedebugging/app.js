const express = require("express");
const authService = require("./src/services/authService");
const AuthCallbackService = require("./src/services/authCallbackService");

var config = require("./src/config");
console.log(config);
const app = express();

app.get("/", (req, res) => {
  const auth = authService.redirectUri();
  res.redirect(auth);
});

app.get("/oauth-github-callback", (req, res) => {
  return AuthCallbackService.callback(req, res);
});

console.log(process.env.PORT);

app.listen(config.port);
console.log(`App listening on http://localhost:${config.envFound.parsed.PORT}`);
