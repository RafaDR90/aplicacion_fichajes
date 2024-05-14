import { defineStore } from "pinia";

export const profileImage = defineStore({
  id: "profileImage",
  state: () => ({
    imageUrl: null,
  }),
  actions: {
    setImageUrl(imageUrl) {
      this.imageUrl = imageUrl;
    },
  },
});