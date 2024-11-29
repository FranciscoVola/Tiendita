const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const mysql = require('mysql');

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'tienda',
});

db.connect(err => {
  if (err) throw err;
  console.log('Conexión a la base de datos exitosa');
});

app.get('/productos', (req, res) => {
  db.query('SELECT * FROM productos', (err, result) => {
    if (err) throw err;
    res.json(result);
  });
});

app.post('/productos', (req, res) => {
  const producto = req.body;
  db.query('INSERT INTO productos SET ?', producto, (err) => {
    if (err) throw err;
    res.send('Producto agregado');
  });
});

app.delete('/productos/:id', (req, res) => {
  const { id } = req.params;
  db.query('DELETE FROM productos WHERE id = ?', id, (err) => {
    if (err) throw err;
    res.send('Producto eliminado');
  });
});

app.listen(3000, () => {
  console.log('Servidor corriendo en http://localhost:3000');
});
