import {translate} from '@vitalets/google-translate-api';
import express from 'express';

const app = express();
app.use(express.json());
app.use(express.urlencoded({extended: true}));

app.listen(3000, () => {
  console.log('Server is listening on port 3000');
});

app.post('/', async (req, res) => {
  const input = req.body?.input;

  if (input) {
    const {text} = await translate(input, {to: 'en'});

    return res.send(text);
  }

  res.send('Not found');
});
