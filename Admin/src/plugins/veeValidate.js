import { defineRule, configure, Form, Field, ErrorMessage } from 'vee-validate'
import { localize } from '@vee-validate/i18n'
import en from '@vee-validate/i18n/dist/locale/en.json'

// Định nghĩa các quy tắc từ `vee-validate` phiên bản 4.x
import * as rules from '@vee-validate/rules'
Object.keys(rules).forEach((rule) => {
  if (typeof rules[rule] === 'function') {
    defineRule(rule, rules[rule])
  }
})

// Định nghĩa các quy tắc tùy chỉnh
defineRule('strongPassword', (value) => {
  const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-={}:'"\\|,.<>/?])(?=.*[a-z]).{6,}$/
  if (!regex.test(value)) {
    return 'Password must include 1 uppercase, 1 number, 1 special character, and be 6+ characters long'
  }
  return true
})

// Cấu hình địa phương hóa
localize({
  en: {
    messages: {
      ...en.messages,
      required: (field) => `This ${field} field is required`,
      password:
        'Password must include 1 uppercase, 1 number, 1 special character, and be 6+ characters long',
      email: 'Please enter a valid email address',
      min: (min) => `This field must have at least ${min} characters`,
      max: (max) => `This field must have at most ${max} characters`,
      numeric: (field) => `This ${field} field must be a number`,
      confirmed: 'The confirmation does not match'
    }
  }
})

// Cấu hình VeeValidate
configure({
  generateMessage: localize('en'),
  validateOnInput: true,
  validateOnBlur: true
})

export default function setupVeeValidate(app) {
  // Đăng ký các thành phần của VeeValidate
  app.component('Field', Field)
  app.component('ValidationForm', Form)
  app.component('ErrorMessage', ErrorMessage)
}
