<template>
  <div class="inscription-section">
    <h2>Rejoignez notre communauté</h2>
    <p>Inscrivez-vous gratuitement dès aujourd'hui</p>
    <div class="inscription-form">
      <h3>Créer un compte</h3>
      <form @submit.prevent="soumettre">
        <input type="text" v-model="form.nom" placeholder="Nom complet" required>
        <input type="email" v-model="form.email" placeholder="Adresse email" required>
        <input type="password" v-model="form.motDePasse" placeholder="Mot de passe" required>
        <select v-model="form.role">
          <option value="">Je suis...</option>
          <option value="acheteur">Acheteuse</option>
          <option value="vendeur">Vendeuse</option>
          <option value="les-deux">Les deux</option>
        </select>
        <button type="submit" :disabled="loading">
          {{ loading ? "Inscription en cours..." : "S'inscrire gratuitement" }}
        </button>
      </form>
      <div v-if="successMsg" class="success-alert">{{ successMsg }}</div>
      <div v-if="errorMsg" class="error-alert">{{ errorMsg }}</div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import api from '../services/axios.js'

const form = reactive({
  nom: '',
  email: '',
  motDePasse: '',
  role: ''
})

const successMsg = ref('')
const errorMsg = ref('')
const loading = ref(false)

async function soumettre() {
  successMsg.value = ''
  errorMsg.value = ''
  
  if (!form.nom || !form.email || !form.motDePasse) {
    errorMsg.value = 'Veuillez remplir tous les champs obligatoires.'
    return
  }
  
  loading.value = true
  try {
    const response = await api.post('/register', {
      name: form.nom,
      email: form.email,
      password: form.motDePasse,
      role: form.role || 'acheteur'
    })
    
    localStorage.setItem('auth_token', response.data.access_token)
    localStorage.setItem('user', JSON.stringify(response.data.user))
    
    successMsg.value = 'Compte créé avec succès !'
    
    // Reset form
    form.nom = ''
    form.email = ''
    form.motDePasse = ''
    form.role = ''
  } catch (error) {
    errorMsg.value = error.response?.data?.message || "Une erreur est survenue lors de l'inscription."
    console.error('Registration error:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.inscription-section {
    background: linear-gradient(135deg, var(--rose-fonce), var(--rose-vif));
    padding: 60px 30px;
    text-align: center;
    color: white;
}

.inscription-section h2 {
    font-size: 32px;
    margin-bottom: 10px;
}

.inscription-section p {
    font-size: 16px;
    opacity: 0.9;
    margin-bottom: 30px;
}

.inscription-form {
    background: white;
    max-width: 460px;
    margin: 0 auto;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.inscription-form h3 {
    color: var(--rose-fonce);
    font-size: 20px;
    margin-bottom: 20px;
}

.inscription-form input,
.inscription-form select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 12px;
    border: 1px solid #e0c0cc;
    border-radius: 10px;
    font-size: 15px;
    color: #333;
    outline: none;
    transition: border-color 0.3s;
}

.inscription-form input:focus,
.inscription-form select:focus {
    border-color: var(--rose-vif);
}

.inscription-form button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--rose-fonce), var(--rose-vif));
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    letter-spacing: 1px;
    transition: opacity 0.3s;
}

.inscription-form button:hover { opacity: 0.9; }
.inscription-form button:disabled { opacity: 0.6; cursor: not-allowed; }

.success-alert {
  color: #2e7d32;
  background-color: #edf7ed;
  padding: 12px;
  border-radius: 10px;
  margin-top: 15px;
  font-size: 14px;
  font-weight: bold;
}

.error-alert {
  color: #c62828;
  background-color: #fde8e8;
  padding: 12px;
  border-radius: 10px;
  margin-top: 15px;
  font-size: 14px;
  font-weight: bold;
}
</style>
