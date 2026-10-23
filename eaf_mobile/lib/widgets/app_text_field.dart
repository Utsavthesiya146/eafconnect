import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';

/// Reusable text field following EAF Connect design system
class AppTextField extends StatelessWidget {
  final TextEditingController? controller;
  final String? label;
  final String? hint;
  final bool obscureText;
  final TextInputType keyboardType;
  final TextInputAction textInputAction;
  final String? Function(String?)? validator;
  final void Function(String)? onSubmitted;
  final void Function(String)? onChanged;
  final IconData? prefixIcon;
  final Widget? suffixIcon;
  final int maxLines;
  final bool readOnly;
  final bool enabled;
  final FocusNode? focusNode;

  const AppTextField({
    super.key,
    this.controller,
    this.label,
    this.hint,
    this.obscureText = false,
    this.keyboardType = TextInputType.text,
    this.textInputAction = TextInputAction.next,
    this.validator,
    this.onSubmitted,
    this.onChanged,
    this.prefixIcon,
    this.suffixIcon,
    this.maxLines = 1,
    this.readOnly = false,
    this.enabled = true,
    this.focusNode,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller:       controller,
      obscureText:      obscureText,
      keyboardType:     keyboardType,
      textInputAction:  textInputAction,
      maxLines:         obscureText ? 1 : maxLines,
      readOnly:         readOnly,
      enabled:          enabled,
      focusNode:        focusNode,
      onFieldSubmitted:  onSubmitted,
      onChanged:        onChanged,
      validator:        validator,
      style: const TextStyle(fontSize: 15, color: AppTheme.textPrimary),
      decoration: InputDecoration(
        labelText:  label,
        hintText:   hint,
        prefixIcon: prefixIcon != null
            ? Icon(prefixIcon, color: AppTheme.textSecondary, size: 20)
            : null,
        suffixIcon: suffixIcon,
      ),
    );
  }
}
