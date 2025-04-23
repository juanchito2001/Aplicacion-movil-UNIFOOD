import 'package:flutter/material.dart';
import '../models/datos.dart';

class CartProvider with ChangeNotifier {
  final List<FoodItem> _items = [];

  List<FoodItem> get items => _items;

  void addToCart(FoodItem item) {
    final index = _items.indexWhere((i) => i.nombre == item.nombre);
    if (index != -1) {
      _items[index].cantidad++;
    } else {
      _items.add(item);
    }
    notifyListeners();
  }

  void removeFromCart(FoodItem item) {
    _items.remove(item);
    notifyListeners();
  }

  void incrementarCantidad(FoodItem item) {
    final index = _items.indexWhere((i) => i.nombre == item.nombre);
    if (index != -1) {
      _items[index].cantidad++;
      notifyListeners();
    }
  }

  void decrementarCantidad(FoodItem item) {
    final index = _items.indexWhere((i) => i.nombre == item.nombre);
    if (index != -1) {
      if (_items[index].cantidad > 1) {
        _items[index].cantidad--;
      } else {
        _items.removeAt(index);
      }
      notifyListeners();
    }
  }

  double get totalPrice =>
      _items.fold(0, (total, current) => total + (current.precio * current.cantidad));

  void clearCart() {
    _items.clear();
    notifyListeners();
  }
}

