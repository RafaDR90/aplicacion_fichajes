import { defineStore } from "pinia";

export const useProfileImage = defineStore({
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