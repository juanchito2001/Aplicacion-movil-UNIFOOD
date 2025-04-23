import 'package:flutter/material.dart';

class CategoryChip extends StatelessWidget {
  final String categoryName;
  final Color backgroundColor;
  final Color labelColor;
  final IconData? icon;

  const CategoryChip({
    Key? key,
    required this.categoryName,
    this.backgroundColor = Colors.greenAccent, // Color por defecto
    this.labelColor = Colors.white, // Color por defecto del texto
    this.icon, // Icono opcional
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 8.0),
      child: Chip(
        label: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (icon != null) ...[
              Icon(icon, color: labelColor), // Muestra el ícono si se proporciona
              SizedBox(width: 4), // Espacio entre el ícono y el texto
            ],
            Text(
              categoryName,
              style: TextStyle(color: labelColor),
            ),
          ],
        ),
        backgroundColor: backgroundColor,
      ),
    );
  }
}

