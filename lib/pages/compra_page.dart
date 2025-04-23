import 'dart:math';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../models/datos.dart';
import '../providers/provedor_carito.dart';
import 'package:provider/provider.dart';

class ConfirmationPage extends StatefulWidget {
  @override
  _ConfirmationPageState createState() => _ConfirmationPageState();
}

class _ConfirmationPageState extends State<ConfirmationPage> {
  bool _isSubmitting = false;
  String _selectedPaymentMethod = '';

  // Genera un código de pedido único
  String _generateOrderCode() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    final random = Random();
    return List.generate(8, (index) => characters[random.nextInt(characters.length)]).join();
  }

  // Enviar pedido al servidor
  Future<void> _placeOrder() async {
    setState(() {
      _isSubmitting = true;
    });

    final cart = Provider.of<CartProvider>(context, listen: false);
    final userId = '123'; // Simulación de ID de usuario; reemplazar con el ID real
    final orderData = {
      'codigoPedido': _generateOrderCode(),
      'idUsuario': userId,
      'fechaPedido': DateTime.now().toIso8601String(),
      'metodoPago': _selectedPaymentMethod,
      'idEstado': 1, // Estado inicial del pedido
      'total': cart.totalPrice,
    };

    try {
      final response = await http.post(
        Uri.parse('http://192.168.137.1:3000/pedidos'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(orderData),
      );

      if (response.statusCode == 201) {
        cart.clearCart();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('¡Compra realizada con éxito!')),
        );
      } else {
        print('Error al procesar la compra: ${response.body}');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error al procesar la compra. Inténtalo de nuevo.')),
        );
      }
    } catch (e) {
      print('Error: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error de red. Inténtalo de nuevo.')),
      );
    } finally {
      setState(() {
        _isSubmitting = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);

    return Scaffold(
      appBar: AppBar(title: Text('Confirmación de Compra')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text('Resumen de la Compra', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            Expanded(
              child: ListView.builder(
                itemCount: cart.items.length,
                itemBuilder: (context, index) {
                  final item = cart.items[index];
                  return ListTile(title: Text(item.nombre), subtitle: Text('\$${item.precio}'));
                },
              ),
            ),
            Text('Total: \$${cart.totalPrice.toStringAsFixed(2)}'),
            DropdownButton<String>(
              isExpanded: true,
              value: _selectedPaymentMethod.isEmpty ? null : _selectedPaymentMethod,
              hint: Text('Selecciona el método de pago'),
              onChanged: (value) => setState(() => _selectedPaymentMethod = value ?? ''),
              items: ['Nequi', 'PayPal', 'Tarjeta de Crédito', 'Efectivo']
                  .map((method) => DropdownMenuItem(value: method, child: Text(method)))
                  .toList(),
            ),
            ElevatedButton(
              onPressed: _isSubmitting ? null : _placeOrder,
              child: _isSubmitting
                  ? CircularProgressIndicator(valueColor: AlwaysStoppedAnimation<Color>(Colors.white))
                  : Text('Confirmar Compra'),
            ),
          ],
        ),
      ),
    );
  }
}


