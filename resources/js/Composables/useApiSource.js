export function useApiSource() {
  const isMock = import.meta.env.VITE_API_MODE === 'mock'
  return { isMock }
}
