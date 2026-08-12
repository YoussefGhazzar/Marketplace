<template>
  <div class="vendre-container">
    <Navbar />

    <div class="page-header">
      <h1>Vendre sur Beautée Shop</h1>
      <p>Publiez votre produit et rejoignez notre communauté de vendeuses</p>
    </div>

    <div class="form-container">
      <h2>Ajouter un produit</h2>

      <form @submit.prevent="soumettre" class="product-form" novalidate>
        <div class="form-group" :class="{ 'has-error': errors.nomVendeuse }">
          <label for="nomVendeuse">Nom de la vendeuse</label>
          <input 
            id="nomVendeuse"
            type="text" 
            v-model="produit.nomVendeuse" 
            placeholder="Votre nom complet"
            @input="validerChamp('nomVendeuse')"
          />
          <span v-if="errors.nomVendeuse" class="error-text">{{ errors.nomVendeuse }}</span>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.typeVendeur }">
          <label for="typeVendeur">Type de vendeur</label>
          <select 
            id="typeVendeur" 
            v-model="produit.typeVendeur"
            @change="validerChamp('typeVendeur')"
          >
            <option value="">Choisir...</option>
            <option>Travail à domicile</option>
            <option>Petite boutique</option>
            <option>Coopérative féminine</option>
            <option>Entreprise</option>
          </select>
          <span v-if="errors.typeVendeur" class="error-text">{{ errors.typeVendeur }}</span>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.nom }">
          <label for="nomProduit">Nom du produit</label>
          <input 
            id="nomProduit"
            type="text" 
            v-model="produit.nom" 
            placeholder="Ex: Crème naturelle à l'argan"
            @input="validerChamp('nom')"
          />
          <span v-if="errors.nom" class="error-text">{{ errors.nom }}</span>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.categorie }">
          <label for="categorie">Catégorie</label>
          <select 
            id="categorie" 
            v-model="produit.categorie"
            @change="validerChamp('categorie')"
          >
            <option value="">Choisir une catégorie...</option>
            <option>Maquillage</option>
            <option>Parfums</option>
            <option>Soins Visage</option>
            <option>Soins Corps</option>
            <option>Jellaba</option>
            <option>Kaftan</option>
            <option>Accessoires</option>
            <option>Autre</option>
          </select>
          <span v-if="errors.categorie" class="error-text">{{ errors.categorie }}</span>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.prix }">
          <label for="prix">Prix (DH)</label>
          <input 
            id="prix"
            type="number" 
            v-model.number="produit.prix" 
            placeholder="Ex: 150"
            @input="validerChamp('prix')"
          />
          <span v-if="errors.prix" class="error-text">{{ errors.prix }}</span>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.description }">
          <label for="description">Description du produit</label>
          <textarea 
            id="description"
            v-model="produit.description" 
            placeholder="Décrivez votre produit, ses ingrédients, ses bienfaits..."
            @input="validerChamp('description')"
          ></textarea>
          <span v-if="errors.description" class="error-text">{{ errors.description }}</span>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.typeOffre }">
          <label for="typeOffre">Type d'offre</label>
          <select 
            id="typeOffre" 
            v-model="produit.typeOffre"
            @change="validerChamp('typeOffre')"
          >
            <option value="">Choisir...</option>
            <option>Vente uniquement</option>
            <option>Echange uniquement</option>
            <option>Vente et Echange</option>
          </select>
          <span v-if="errors.typeOffre" class="error-text">{{ errors.typeOffre }}</span>
        </div>

        <div class="form-group">
          <label>Photo du produit</label>
          <div 
            class="upload-area" 
            :class="{ 'dragover': isDragOver, 'has-preview': photoPreview }"
            @dragover.prevent="isDragOver = true"
            @dragleave.prevent="isDragOver = false"
            @drop.prevent="gererDrop"
            @click="declencherSelectionFichier"
          >
            <input 
              id="photo"
              type="file" 
              accept="image/*" 
              ref="fileInput"
              @change="gererPhoto"
              class="hidden-file-input"
            />
            
            <div v-if="!photoPreview" class="upload-placeholder">
              <span class="upload-icon">📸</span>
              <p class="upload-text">Glissez-déposez votre photo ici, ou <span>parcourez vos fichiers</span></p>
              <p class="upload-hint">Formats supportés: JPG, PNG, WEBP. Max 5Mo</p>
            </div>
            
            <div v-else class="upload-preview" @click.stop>
              <img :src="photoPreview" alt="Aperçu du produit" />
              <div class="preview-overlay">
                <button type="button" @click.stop="retirerPhoto" class="btn-remove">
                  ✕ Retirer
                </button>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-submit" :disabled="loading">
          <span>{{ loading ? 'Publication...' : 'Publier le produit' }}</span>
        </button>
      </form>

      <Transition name="fade-slide">
        <div v-if="afficherSucces" class="success-msg">
          <span class="success-icon">✓</span>
          Votre produit a été publié avec succès !
        </div>
      </Transition>

      <Transition name="fade-slide">
        <div v-if="errorMsg" class="error-msg">
          <span class="error-icon">✕</span>
          {{ errorMsg }}
        </div>
      </Transition>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'
import api from '../services/axios.js'

const produitInitial = {
  nomVendeuse: '',
  typeVendeur: '',
  nom: '',
  categorie: '',
  prix: '',
  description: '',
  typeOffre: '',
  photo: null
}

const produit = ref({ ...produitInitial })
const errors = reactive({
  nomVendeuse: '',
  typeVendeur: '',
  nom: '',
  categorie: '',
  prix: '',
  description: '',
  typeOffre: ''
})

const fileInput = ref(null)
const isDragOver = ref(false)
const photoPreview = ref(null)
const afficherSucces = ref(false)
const loading = ref(false)
const errorMsg = ref('')

const validerChamp = (champ) => {
  if (champ === 'nomVendeuse') {
    errors.nomVendeuse = produit.value.nomVendeuse.trim() ? '' : 'Le nom de la vendeuse est requis'
  } else if (champ === 'typeVendeur') {
    errors.typeVendeur = produit.value.typeVendeur ? '' : 'Le type de vendeur est requis'
  } else if (champ === 'nom') {
    errors.nom = produit.value.nom.trim() ? '' : 'Le nom du produit est requis'
  } else if (champ === 'categorie') {
    errors.categorie = produit.value.categorie ? '' : 'La catégorie est requise'
  } else if (champ === 'prix') {
    if (produit.value.prix === null || produit.value.prix === '') {
      errors.prix = 'Le prix est requis'
    } else if (produit.value.prix < 0) {
      errors.prix = 'Le prix ne peut pas être négatif'
    } else {
      errors.prix = ''
    }
  } else if (champ === 'description') {
    errors.description = produit.value.description.trim() ? '' : 'La description est requise'
  } else if (champ === 'typeOffre') {
    errors.typeOffre = produit.value.typeOffre ? '' : "Le type d'offre est requis"
  }
}

const validerFormulaire = () => {
  validerChamp('nomVendeuse')
  validerChamp('typeVendeur')
  validerChamp('nom')
  validerChamp('categorie')
  validerChamp('prix')
  validerChamp('description')
  validerChamp('typeOffre')

  return !Object.values(errors).some(error => error !== '')
}

const declencherSelectionFichier = () => {
  fileInput.value.click()
}

const traiterFichier = (file) => {
  if (file && file.type.startsWith('image/')) {
    produit.value.photo = file
    const reader = new FileReader()
    reader.onload = (e) => {
      photoPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const gererPhoto = (e) => {
  const file = e.target.files[0]
  traiterFichier(file)
}

const gererDrop = (e) => {
  isDragOver.value = false
  const file = e.dataTransfer.files[0]
  traiterFichier(file)
}

const retirerPhoto = () => {
  produit.value.photo = null
  photoPreview.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const resetForm = () => {
  produit.value = { ...produitInitial }
  photoPreview.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
  Object.keys(errors).forEach(key => errors[key] = '')
}

const soumettre = async () => {
  if (validerFormulaire()) {
    loading.value = true
    errorMsg.value = ''
    try {
      const formData = new FormData()
      formData.append('nomVendeuse', produit.value.nomVendeuse)
      formData.append('typeVendeur', produit.value.typeVendeur)
      formData.append('nom', produit.value.nom)
      formData.append('categorie', produit.value.categorie)
      formData.append('prix', produit.value.prix)
      formData.append('description', produit.value.description)
      formData.append('typeOffre', produit.value.typeOffre)
      if (produit.value.photo) {
        formData.append('photo', produit.value.photo)
      }

      const response = await api.post('/products', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })

      // Update local storage too so offline/other components stay in sync
      const publishedProducts = JSON.parse(localStorage.getItem('produits_publies')) || []
      const newProduct = response.data.product
      publishedProducts.push({
        id: newProduct.id,
        nomVendeuse: newProduct.nom_vendeuse,
        typeVendeur: newProduct.type_vendeur,
        nom: newProduct.nom,
        categorie: newProduct.categorie,
        prix: newProduct.prix,
        description: newProduct.description,
        typeOffre: newProduct.type_offre,
        photo: newProduct.photo_url || photoPreview.value
      })
      localStorage.setItem('produits_publies', JSON.stringify(publishedProducts))

      window.dispatchEvent(new Event('produits-mis-a-jour'))

      afficherSucces.value = true
      resetForm()
      
      setTimeout(() => {
        afficherSucces.value = false
      }, 4500)
    } catch (error) {
      errorMsg.value = error.response?.data?.message || "Une erreur est survenue lors de la publication du produit."
      console.error('Error publishing product:', error)
    } finally {
      loading.value = false
    }
  }
}
</script>

<style scoped>
.vendre-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #fff5f8;
}

.page-header {
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  text-align: center;
  padding: 60px 20px;
}

.page-header h1 {
  font-size: 40px;
  margin-bottom: 10px;
  letter-spacing: 2px;
  font-family: 'Georgia', serif;
}

.page-header p {
  font-size: 17px;
  opacity: 0.9;
  font-family: 'Georgia', serif;
}

.form-container {
  max-width: 650px;
  width: 90%;
  margin: 60px auto;
  background: white;
  padding: 50px;
  border-radius: 20px;
  box-shadow: 0 6px 30px rgba(139, 26, 74, 0.15);
  box-sizing: border-box;
}

.form-container h2 {
  color: #8b1a4a;
  font-size: 26px;
  margin-bottom: 30px;
  text-align: center;
  font-family: 'Georgia', serif;
}

.product-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-size: 14px;
  color: #555;
  font-weight: bold;
  font-family: 'Georgia', serif;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 13px 15px;
  border: 1px solid #e0c0cc;
  border-radius: 10px;
  font-size: 15px;
  font-family: 'Georgia', serif;
  color: #333;
  outline: none;
  background-color: #fff;
  transition: border-color 0.3s, box-shadow 0.3s;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #c2185b;
  box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.15);
}

.form-group textarea {
  height: 120px;
  resize: vertical;
}

/* File Upload Area */
.hidden-file-input {
  display: none;
}

.upload-area {
  border: 2px dashed #e0c0cc;
  border-radius: 12px;
  background: #fff5f8;
  padding: 30px 20px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  min-height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.upload-area:hover, .upload-area.dragover {
  border-color: #c2185b;
  background: #ffebf1;
}

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.upload-icon {
  font-size: 36px;
}

.upload-text {
  font-size: 15px;
  color: #6b6375;
  margin: 0;
  font-family: 'Georgia', serif;
}

.upload-text span {
  color: #c2185b;
  font-weight: bold;
}

.upload-hint {
  font-size: 12px;
  color: #9ca3af;
  margin: 0;
}

.upload-preview {
  width: 100%;
  height: 100%;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.upload-preview img {
  max-width: 100%;
  max-height: 250px;
  border-radius: 8px;
  object-fit: contain;
}

.preview-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 8px;
}

.upload-preview:hover .preview-overlay {
  opacity: 1;
}

.btn-remove {
  background: #ff3366;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  transition: background 0.2s;
}

.btn-remove:hover {
  background: #e02454;
}

/* Button Submit */
.btn-submit {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 17px;
  cursor: pointer;
  font-family: 'Georgia', serif;
  font-weight: bold;
  letter-spacing: 1px;
  transition: opacity 0.3s, transform 0.2s;
  margin-top: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.btn-submit:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}

.btn-submit:active {
  transform: translateY(0);
}

/* Error states */
.has-error input,
.has-error select,
.has-error textarea {
  border-color: #ff3366 !important;
  background-color: #fffafb;
}

.error-text {
  font-size: 12px;
  color: #ff3366;
  font-family: 'Georgia', serif;
  margin-top: -2px;
  text-align: left;
}

/* Success Message */
.success-msg {
  background: #e8f5e9;
  color: #2e7d32;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  font-size: 15px;
  margin-top: 20px;
  font-family: 'Georgia', serif;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
}

.success-icon {
  font-weight: bold;
  font-size: 18px;
}

/* Error Message */
.error-msg {
  background: #fde8e8;
  color: #c62828;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  font-size: 15px;
  margin-top: 20px;
  font-family: 'Georgia', serif;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(198, 40, 40, 0.1);
}

.error-icon {
  font-weight: bold;
  font-size: 18px;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Transitions */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.4s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media (max-width: 768px) {
  .page-header {
    padding: 40px 15px;
  }

  .page-header h1 {
    font-size: 30px;
  }

  .form-container {
    padding: 30px 20px;
    margin: 30px auto;
  }
}
</style>
