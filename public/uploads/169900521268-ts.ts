//auth js

import useSWR from "swr";
import axios from "../lib/axios";
import { useRouter } from "next/router";

export const useAuth = ({} = {}) => {
  const router = useRouter();

  const {
    data: user,
    error,
    mutate,
  } = useSWR("/api/user", () =>
    axios
      .get("/api/user")
      .then((res) => res.data)
      .catch((error) => {
        if (error.response.status !== 409) throw error;

        router.push("/verify-email");
      })
  );

  const csrf = () => axios.get("/sanctum/csrf-cookie");

  const register = async ({ setErrors, ...props }) => {
    await csrf();

    setErrors([]);

    axios
      .post("/register", props)
      .then(() => mutate())
      .catch((error) => {
        if (error.response.status !== 422) throw error;

        setErrors(error.response.data.errors);
      });
  };

  return {
    user,
    register,
  };
};



====================
//apiSlice.ts
import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';

export const apiSlice = createApi({
  reducerPath: 'api',
  baseQuery: fetchBaseQuery({
    baseUrl: process.env.NEXT_PUBLIC_SERVER_URI,
  }),

  endpoints: (builder) => ({}),
});
export const {} = apiSlice;



=====================
//classApi.tsx
import { apiSlice } from '../api/apiSlice';

//menambah endpoin pada apiSlice
export const classSubjectApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    getAllClass: builder.query({
      query: () => ({
        url: 'class',
        method: 'GET',
        credentials: 'include' as const,
      }),
    }),
    createClass: builder.mutation({
      query: (data) => ({
        url: 'class',
        method: 'POST',
        body: data,
        credentials: 'include' as const,
      }),
    }),
    editClass: builder.mutation({
      query: ({ id, data }) => ({
        url: `class/${id}`,
        method: 'PUT',
        body: data,
        credentials: 'include' as const,
      }),
    }),
    deleteClass: builder.mutation({
      query: (id) => ({
        url: `class/${id}`,
        method: 'DELETE',
        credentials: 'include' as const,
      }),
    }),
  }),
});

export const {
  useGetAllClassQuery,
  useCreateClassMutation,
  useEditClassMutation,
  useDeleteClassMutation,
} = classSubjectApi;

