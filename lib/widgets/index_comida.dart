import 'package:flutter/material.dart';
import '../models/datos.dart';

class FoodCard extends StatelessWidget {
  final FoodItem foodItem;
  final String imagen;
  final double precio;
  final VoidCallback onAddToCart;

  const FoodCard({
    required this.foodItem,
    required this.imagen,
    required this.precio,
    required this.onAddToCart,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 5,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Image.network(imagen),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Text(foodItem.nombre),
          ),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Text('\$${precio.toStringAsFixed(2)}'),
          ),
          ElevatedButton(
            onPressed: onAddToCart,
            child: Text('Agregar al carrito'),
          ),
        ],
      ),
    );
  }
}

