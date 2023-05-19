import {translate} from '@vitalets/google-translate-api';
import express from 'express';
import createHttpProxyAgent from 'http-proxy-agent';
import fs from 'fs';

const app = express();
app.use(express.json());
app.use(express.urlencoded({extended: true}));

app.listen(3000, () => {
  console.log('Server is listening on port 3000');
});

app.post('/', async (req, res) => {
  const input = req.body?.input;

  if (input) {
    let text = input;
    let translated = '';
    try {
      text = await translate(input, {to: 'en'});
    } catch (e) {
      text = await callWithProxy(input);
    }

    translated = text;

    return res.send(translated);
  }

  res.send('Not found');
});

async function callWithProxy(input, usedProxies = []) {
  const list = fs.readFileSync('list.txt', 'utf8').split('\n');
  const proxies = list.filter((proxy) => !usedProxies.includes(proxy));
  const proxy = proxies[0];
  if (!proxy) {
    return input;
  }
  console.log('trying with proxy: ', proxy);

  const agent = createHttpProxyAgent(`http://${proxy}`);

  let translated = '';
  try {
    const {text} = await translate(input, {to: 'en', agent});
    translated = text;
  } catch (e) {
    usedProxies.push(proxy);
    translated = await callWithProxy(input, usedProxies);
  }

  return translated;
}
