import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;

class ApiService {
  final String baseUrl = 'http://192.168.56.1:3000/platos'; // Cambia localhost por tu IP

  Future<List<dynamic>> fetchPlatos() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/platos')).timeout(Duration(seconds: 10));

      if (response.statusCode == 200) {
        return json.decode(response.body);
      } else {
        throw Exception('Error ${response.statusCode}: ${response.reasonPhrase}');
      }
    } on SocketException {
      throw Exception('Error de conexión. Por favor, verifica tu conexión a Internet.');
    } on TimeoutException {
      throw Exception('Tiempo de espera agotado. El servidor no respondió.');
    } catch (e) {
      throw Exception('Ocurrió un error inesperado: $e');
    }
  }

  Future<void> addPlato(String nombre, String descripcion, double precio) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/platos'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'nombre': nombre,
          'descripcion': descripcion,
          'precio': precio,
        }),
      );

      if (response.statusCode != 201) {
        throw Exception('Error ${response.statusCode}: ${response.reasonPhrase}');
      }
    } on SocketException {
      throw Exception('Error de conexión. Por favor, verifica tu conexión a Internet.');
    } catch (e) {
      throw Exception('Ocurrió un error al agregar el plato: $e');
    }
  }
}

