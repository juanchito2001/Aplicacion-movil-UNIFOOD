import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/provedor_carito.dart';
import 'compra_page.dart';

class CartPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: Text('Carrito'),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: cart.items.length,
              itemBuilder: (context, index) {
                final item = cart.items[index];
                return Card(
                  elevation: 4,
                  margin: EdgeInsets.symmetric(vertical: 8, horizontal: 16),
                  child: ListTile(
                    contentPadding: EdgeInsets.all(16),
                    title: Text(
                      item.nombre,
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    subtitle: Text(
                      '\$${item.precio.toStringAsFixed(2)}',
                      style: TextStyle(color: Colors.green, fontSize: 16),
                    ),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        IconButton(
                          icon: Icon(Icons.remove_circle, color: Colors.red),
                          onPressed: item.cantidad > 1
                              ? () => cart.decrementarCantidad(item)
                              : () => cart.removeFromCart(item),
                        ),
                        Text(
                          '${item.cantidad}',
                          style: TextStyle(fontSize: 16),
                        ),
                        IconButton(
                          icon: Icon(Icons.add_circle, color: Colors.blue),
                          onPressed: () => cart.incrementarCantidad(item),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Total:',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                ),
                Text(
                  '\$${cart.totalPrice.toStringAsFixed(2)}',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.green),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20.0),
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                padding: EdgeInsets.symmetric(vertical: 15),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(30),
                ),
              ),
              onPressed: () {
                if (cart.items.isEmpty) {
                  showDialog(
                    context: context,
                    builder: (ctx) => AlertDialog(
                      title: Text('Carrito vacío'),
                      content: Text('No tienes productos en tu carrito.'),
                      actions: [
                        TextButton(
                          onPressed: () => Navigator.pop(ctx),
                          child: Text('OK'),
                        ),
                      ],
                    ),
                  );
                } else {
                  Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => ConfirmationPage()),
                  );
                }
              },
              child: Text('Finalizar Compra', style: TextStyle(fontSize: 18)),
            ),
          ),
          SizedBox(height: 20),
        ],
      ),
    );
  }
}



